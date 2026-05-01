<?php

namespace App\Http\Controllers\Frontend;


use Exception;
use App\Models\Cart;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Address;
use App\Models\OrderProduct;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ShiprocketService;
use App\Http\Controllers\Controller;
use App\Models\ReturnProduct;
use App\Models\ReturnStatusLog;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $shiprocket;

    public function __construct(ShiprocketService $shiprocket)
    {
        $this->shiprocket = $shiprocket;
    }

    public function checkout(Request $Request)
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->orderBy('default', 'ASC')->get();

        return view('Frontend.Pages.checkout', compact('user', 'addresses'));
    }

    public function initiatePayment(Request $request)
    {
        \Log::info('Stripe Initiate Payment Request', ['data' => $request->all()]);
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:1',
            'accept_terms'   => 'required|boolean',
            'address_id'     => 'required|exists:addresses,id',
            'coupon_code_id' => 'nullable|exists:coupon_codes,id'
        ]);

        $user = Auth::user();
        $currencyCode = session('currency', 'GBP');
        $currencyModel = \App\Models\Currency::where('code', $currencyCode)->where('is_active', true)->first() ?: \App\Models\Currency::where('code', 'GBP')->first();
        $rate = $currencyModel->exchange_rate ?? 1.0;
        $currency = $currencyModel->code ?? 'GBP';

        try {
            $stripeSecret = config('services.stripe.secret');
            \Log::info('Stripe Secret present: ' . ($stripeSecret ? 'YES' : 'NO'));
            Stripe::setApiKey($stripeSecret);

            $coupon = null;
            if (!empty($validated['coupon_code_id'])) {
                $coupon = \App\Models\CouponCode::find($validated['coupon_code_id']);
            }
            $discount_percentage = $coupon ? ($coupon->percentage ?? 0) : 0;

            $line_items = [];
            foreach ($user->carts as $cart) {
                $cart_data = getCartData($cart);
                
                $original_price = $cart_data['price'];
                $discounted_price = $original_price - ($original_price * ($discount_percentage / 100));

                // Price is in GBP, convert and multiply by 100 for subunits
                $unit_amount = round($discounted_price * $rate * 100);

                $line_items[] = [
                    'price_data' => [
                        'currency'     => strtolower($currency),
                        'product_data' => [
                            'name' => $cart->product->name . ' (' . $cart_data['property_values'] . ')',
                        ],
                        'unit_amount' => $unit_amount,
                    ],
                    'quantity' => $cart->quantity,
                ];
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => $line_items,
                'mode'                 => 'payment',
                'success_url'          => route('frontend.orders.stripe-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => route('frontend.orders.stripe-cancel'),
                'metadata' => [
                    'user_id'        => $user->id,
                    'address_id'     => $validated['address_id'],
                    'coupon_code_id' => $validated['coupon_code_id'],
                    'currency'       => $currency,
                    'rate'           => $rate
                ]
            ]);

            return response()->json([
                'status'      => 'success',
                'session_id'  => $session->id,
                'id'          => $session->id,
                'session_url' => $session->url
            ]);
        } catch (\Exception $e) {
            \Log::error('Stripe Initiate Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function stripeSuccess(Request $request)
    {
        $session_id = $request->get('session_id');
        if (!$session_id) {
            return redirect()->route('frontend.orders.checkout')->with('error', 'Invalid session');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($session_id);

        if ($session->payment_status === 'paid') {
            $user = Auth::user();
            $metadata = $session->metadata;

            $carts = Cart::where('user_id', $user->id)->get();

            $prefix = '152356';
            $lastOrder = Order::where('order_number', 'like', $prefix . '%')
                ->latest('id')
                ->first();

            $nextNumber = 1;
            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->order_number, strlen($prefix));
                $nextNumber = $lastNumber + 1;
            }

            $order_number = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $order = Order::create([
                'user_id'                  => $user->id,
                'address_id'               => $metadata->address_id,
                'coupon_code_id'           => $metadata->coupon_code_id,
                'order_number'             => $order_number,
                'stripe_session_id'        => $session->id,
                'stripe_payment_intent_id' => $session->payment_intent,
                'shiprocket_status'        => 'NEW',
                'gst'                      => ($session->amount_total / 100 / 1.18) * 0.18,
                'total_amount'             => ($session->amount_total / 100) / $metadata->rate, // Store in base currency GBP
                'currency_code'            => $metadata->currency ?? 'GBP',
                'exchange_rate'            => $metadata->rate ?? 1.0,
                'payment_status'           => 'COMPLETED',
                'payment_method'           => 'GATEWAY'
            ]);

            $coupon = null;
            if (!empty($metadata->coupon_code_id)) {
                $coupon = \App\Models\CouponCode::find($metadata->coupon_code_id);
            }
            $discount_percentage = $coupon ? ($coupon->percentage ?? 0) : 0;

            foreach ($carts as $cart) {
                $cart_data = getCartData($cart);
                
                $original_price = $cart_data['price'];
                $discounted_price = $original_price - ($original_price * ($discount_percentage / 100));

                OrderProduct::create([
                    'order_id'             => $order->id,
                    'product_id'           => $cart->product_id,
                    'property_values'      => $cart->property_values,
                    'property_value_names' => $cart_data['property_values'],
                    'quantity'             => $cart->quantity,
                    'price'                => $discounted_price,
                    'gst'                  => $discounted_price * 0.18,
                    'total_amount'         => $cart->quantity * $discounted_price,
                    'coupon_code_id'       => $metadata->coupon_code_id,
                ]);

                $productPriceQuery = ProductPrice::where('product_id', $cart->product_id);
                foreach ($cart->property_values as $value) {
                    $productPriceQuery->whereJsonContains('property_values', (int)$value);
                }
                $productPrice = $productPriceQuery->first();

                if ($productPrice) {
                    $newStock = max(0, $productPrice->stock - $cart->quantity);
                    $productPrice->stock = $newStock;
                    $productPrice->save();
                }
            }

            Cart::where('user_id', $user->id)->delete();

            $data = [
                'subject'        => 'Order Confirmation - #' . $order->order_number,
                'order_number'   => $order->order_number,
                'customer_name'  => $order->user->full_name,
                'total_amount'   => $order->total_amount,
                'order_products' => $order->products,
            ];

            $variables = $order->order_number . '|' . config('app.url');
            send_sms($order->user->mobile, $variables, 185480);
            EmailService::sendEmail($user->email, 'emails.order-placed', $data);

            return redirect()->route('frontend.orders')->with('success', 'Order placed successfully');
        }

        return redirect()->route('frontend.orders.checkout')->with('error', 'Payment failed');
    }

    public function stripeCancel()
    {
        return redirect()->route('frontend.orders.checkout')->with('error', 'Payment was cancelled');
    }

    public function verifyPayment(Request $request)
    {
        // Deprecated for Stripe Checkout flow
        return response()->json(['status' => 'error', 'message' => 'Deprecated method'], 404);
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderByDesc('id')->get();

        return view('Frontend.Pages.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        $tracking_activities = $order->trackingHistories()->orderBy('created_at', 'ASC')->get();

        return view('Frontend.Pages.order-details', compact('order', 'tracking_activities'));
    }

    public function pdf(Order $order)
    {
        $pdf = Pdf::loadView('Frontend.Pages.order-pdf', compact('order'));
        return $pdf->stream('order-' . $order->order_number . '.pdf');
    }

    public function cancelOrder(Request $request, Order $order)
    {
        $user = Auth::user();
        $shiprocket_order_id = $order->shiprocket_order_id;
        $sms_template_id = 185372;
        $variable = $order->order_number . '|' . config('app.url');

        if ($shiprocket_order_id) {
            $response = $this->shiprocket->cancelOrder($shiprocket_order_id);

            if (!empty($response) && ($response['status_code'] ?? null) === 200) {
                $order->update([
                    'shiprocket_status' => 'Cancelled',
                    'cancellation_reason' => $request->cancellation_reason,
                ]);

                send_sms($order->user->mobile, $variable, $sms_template_id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Order cancelled successfully.',
                ]);
            }
        }

        foreach ($order->products as $orderProduct) {
            $productPriceQuery = ProductPrice::where('product_id', $orderProduct->product_id);
            foreach ($orderProduct->property_values as $value) {
                $productPriceQuery->whereJsonContains('property_values', (int)$value);
            }
            $productPrice = $productPriceQuery->first();

            if ($productPrice) {
                $productPrice->stock += $orderProduct->quantity;
                $productPrice->save();
            }
        }

        $order->update([
            'shiprocket_status' => 'Cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        $data = [
            'subject' => 'Order Cancelled - #' . $order->order_number,
            'order_number' => $order->order_number,
            'customer_name' => $order->user->full_name,
            'total_amount' => $order->total_amount,
            'order_products' => $order->products,
        ];

        send_sms($order->user->mobile, $variable, $sms_template_id);

        EmailService::sendEmail($user->email, 'emails.userorder-cancel', $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Order cancelled successfully',
        ]);
    }

    public function returnOrder(Request $request, Order $order)
    {
        $request->validate([
            'order_product_id' => 'required|exists:order_products,id',
            'return_quantity' => 'required|integer|min:1',
            'remark' => 'nullable|string'
        ]);

        $order_product_id = $request->order_product_id;
        $return_quantity = $request->return_quantity;

        // Check if order is delivered and within 7 days
        if ($order->status !== 'Delivered') {
            return response()->json(['status' => 'error', 'message' => 'Only delivered orders can be returned.'], 400);
        }

        if ($order->updated_at->diffInDays() > 7) {
            return response()->json(['status' => 'error', 'message' => 'Return period has expired (7 days).'], 400);
        }

        $orderProduct = OrderProduct::where('order_id', $order->id)
            ->where('id', $order_product_id)
            ->first();

        if (!$orderProduct) {
            return response()->json(['status' => 'error', 'message' => 'Product not found in this order.'], 400);
        }

        $alreadyReturned = ReturnProduct::where('order_product_id', $order_product_id)->sum('return_quantity');
        if (($alreadyReturned + $return_quantity) > $orderProduct->quantity) {
            return response()->json(['status' => 'error', 'message' => 'Return quantity exceeds purchased quantity.'], 400);
        }

        $fixedPrefix = 'R543210+';
        $last_return_number = ReturnProduct::whereNotNull('return_number')
            ->where('return_number', 'like', $fixedPrefix . '%')
            ->orderBy('id', 'desc')
            ->value('return_number');

        $last_number = 0;
        if ($last_return_number && preg_match('/\+(\d+)$/', $last_return_number, $matches)) {
            $last_number = (int) $matches[1];
        }
        $next_number = $fixedPrefix . str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);

        $return_product = ReturnProduct::create([
            'order_id' => $order->id,
            'order_product_id' => $order_product_id,
            'return_quantity' => $return_quantity,
            'remark' => $request->remark,
            'return_number' => $next_number,
            'status' => 'RETURN_IN_PROGRESS'
        ]);

        ReturnStatusLog::create([
            'return_product_id' => $return_product->id,
            'status' => 'RETURN_IN_PROGRESS',
        ]);

        // Email to customer
        $emailData = [
            'subject' => 'Return Initiated - #' . $order->order_number,
            'order_number' => $order->order_number,
            'customer_name' => $order->user->full_name,
            'product_name' => $orderProduct->product->name,
            'quantity' => $return_quantity,
            'remark' => $request->remark,
        ];
        EmailService::sendEmail($order->user->email, 'emails.order-return', $emailData);

        return response()->json([
            'status' => 'success',
            'message' => 'Return request submitted successfully.'
        ]);
    }
}
