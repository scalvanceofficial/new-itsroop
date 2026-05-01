<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Services\ShiprocketService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShiprocketController extends Controller
{
    protected $shiprocket;

    public function __construct(ShiprocketService $shiprocket)
    {
        $this->shiprocket = $shiprocket;
    }

    /**
     * Create an Order in Shiprocket
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'pickup_location' => 'required|string',
            'length' => 'required|numeric',
            'breadth' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 400);
        }

        $order->update([
            'length' => $request->length,
            'breadth' => $request->breadth,
            'height' => $request->height,
            'weight' => $request->weight,
        ]);

        $order_products = $order->products->map(function ($order_product) {
            return [
                "name" => $order_product->product->name,
                "sku" => $order_product->product->sku,
                "units" => $order_product->quantity,
                "selling_price" => $order_product->price,
                "discount" => "",
                "tax" => $order_product->gst,
                "hsn" => $order_product->product->hsn
            ];
        });

        $shiprocket_order_data = [
            "order_id" => $order->order_number,
            "order_date" => Carbon::parse($order->created_at)->format('Y-m-d'),
            "pickup_location" => $request->pickup_location,
            "channel_id" => "",
            "comment" => "",
            "billing_customer_name" => $order->address->recipient_first_name,
            "billing_last_name" => $order->address->recipient_last_name,
            "billing_address" => $order->address->address_line_1,
            "billing_address_2" => $order->address->address_line_2,
            "billing_city" => $order->address->city,
            "billing_pincode" => $order->address->pincode,
            "billing_state" => "Maharashtra",
            "billing_country" => "India",
            "billing_email" => $order->user->email,
            "billing_phone" => $order->user->mobile,
            "shipping_is_billing" => true,
            "shipping_customer_name" => "",
            "shipping_last_name" => "",
            "shipping_address" => "",
            "shipping_address_2" => "",
            "shipping_city" => "",
            "shipping_pincode" => "",
            "shipping_country" => "",
            "shipping_state" => "",
            "shipping_email" => "",
            "shipping_phone" => "",
            "order_items" => $order_products,
            "payment_method" => "Prepaid",
            "shipping_charges" => $order->shipping_charge,
            "giftwrap_charges" => 0,
            "transaction_charges" => 0,
            "total_discount" => 0,
            "sub_total" => $order->total_amount,
            "length" => $order->length,
            "breadth" => $order->breadth,
            "height" => $order->height,
            "weight" => $order->weight,
        ];

        $response = $this->shiprocket->createOrder($shiprocket_order_data);

        if (!empty($response) && isset($response['status']) && $response['status'] == 'NEW') {
            $order->update([
                'shiprocket_order_id' => $response['order_id'],
                'shiprocket_shipment_id' => $response['shipment_id'],
                'shiprocket_status' => $response['status'],
                'shiprocket_order_create_response' => $response,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Shiprocket order created successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $response['message'] ?? 'Failed to create Shiprocket order.',
            ], 422);
        }
    }



    public function updateOrder(Request $request)
    {
        $request->validate([
            'length' => 'required|numeric',
            'breadth' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        $order = Order::find($request->update_order_id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 400);
        }

        $order->update([
            'length' => $request->length,
            'breadth' => $request->breadth,
            'height' => $request->height,
            'weight' => $request->weight,
        ]);

        $order_products = $order->products->map(function ($order_product) {
            return [
                "name" => $order_product->product->name,
                "sku" => $order_product->product->sku,
                "units" => $order_product->quantity,
                "selling_price" => $order_product->price,
                "discount" => "",
                "tax" => $order_product->gst,
                "hsn" => $order_product->product->hsn
            ];
        });

        $shiprocket_order_data = [
            "order_id" => $order->order_number,
            "order_date" => Carbon::parse($order->created_at)->format('Y-m-d'),
            "pickup_location" => 'Home',
            "channel_id" => "",
            "comment" => "",
            "billing_customer_name" => $order->user->first_name,
            "billing_last_name" => $order->user->last_name,
            "billing_address" => $order->address->address_line_1,
            "billing_address_2" => $order->address->address_line_2,
            "billing_city" => $order->address->city,
            "billing_pincode" => $order->address->pincode,
            "billing_state" => "Maharashtra",
            "billing_country" => "India",
            "billing_email" => $order->user->email,
            "billing_phone" => $order->user->mobile,
            "shipping_is_billing" => true,
            "shipping_customer_name" => "",
            "shipping_last_name" => "",
            "shipping_address" => "",
            "shipping_address_2" => "",
            "shipping_city" => "",
            "shipping_pincode" => "",
            "shipping_country" => "",
            "shipping_state" => "",
            "shipping_email" => "",
            "shipping_phone" => "",
            "order_items" => $order_products,
            "payment_method" => "Prepaid",
            "shipping_charges" => $order->shipping_charge,
            "giftwrap_charges" => 0,
            "transaction_charges" => 0,
            "total_discount" => 0,
            "sub_total" => $order->total_amount,
            "length" => $order->length,
            "breadth" => $order->breadth,
            "height" => $order->height,
            "weight" => $order->weight,
        ];

        $response = $this->shiprocket->updateOrder($shiprocket_order_data);

        if (!empty($response) && isset($response['success'])) {
            return response()->json([
                'status' => 'success',
                'message' => 'Shiprocket order updated successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $response['message'] ?? 'Failed to update Shiprocket order.',
            ], 422);
        }
    }

    /**
     * Track an Order
     */
    public function trackOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 400);
        }

        $tracking_activities = [];

        if (isset($order->shiprocket_tracking_response['tracking_data']['shipment_track_activities'])) {
            $tracking_activities = $order->shiprocket_tracking_response['tracking_data']['shipment_track_activities'];
        }

        return view('Admin.Orders.tracking-table', compact('tracking_activities'));
    }

    /**
     * Cancel an Order
     */

    public function cancelOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 400);
        }

        $response = $this->shiprocket->cancelOrder($order->shiprocket_order_id);

        if (!empty($response) && isset($response['status_code']) && $response['status_code'] == 200) {
            $order->update([
                'shiprocket_status' => 'Cancelled',
                'status' => 'Cancelled'
            ]);

            $data = [
                'subject' => 'Order Cancelled - #' . $order->order_number,
                'order_number' => $order->order_number,
                'customer_name' => $order->user->full_name,
                'total_amount' => $order->total_amount,
                'order_products' => $order->products,
            ];

            $variable = $order->order_number . '|' . config('app.url');

            send_sms($order->user->mobile, $variable, 185372);

            EmailService::sendEmail($order->user->email, 'emails.adminorder-cancel', $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Shiprocket order cancelled successfully.',
            ]);
        }
    }
}
