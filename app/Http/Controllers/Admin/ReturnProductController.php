<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;

use App\Models\Order;
use Illuminate\Support\Str;
use App\Models\OrderProduct;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Models\ReturnProduct;
use App\Services\EmailService;
use App\Models\ReturnStatusLog;
use App\Exports\ReturnProductExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReturnProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.ReturnProducts.index');
    }

    // Fetch data for DataTable
    public function data(Request $request)
    {
        $query = ReturnProduct::where('id', '!=', 0)
            ->when($request->from_date && $request->to_date, function ($query) use ($request) {
                $from = Carbon::parse($request->from_date)->startOfDay();
                $to = Carbon::parse($request->to_date)->endOfDay();
                // dd($from, $to);
                return $query->whereBetween('created_at', [$from, $to]);
            })

            ->when($request->status, function ($query, $status) {
                if ($status === 'PENDING') {
                    $query->whereIn('status', ['RETURN_IN_PROGRESS', 'PRODUCT_RECEIVED', 'REFUND_INITIATE', 'REFUND_PROCESSED']);
                } elseif ($status === 'COMPLETED') {
                    $query->where('status', 'REFUND_COMPLETED');
                }
            })
            ->orderByDesc('created_at');

        return DataTables::eloquent($query)
            ->editColumn('customer', function ($return_product) {
                return $return_product->order->user->full_name;
            })
            ->addColumn('created_at', function ($return_product) {
                return toIndianDateTime($return_product->created_at);
            })
            ->addColumn('order_number', function ($return_product) {
                return $return_product->order->order_number;
            })
            ->addColumn('return_number', function ($return_product) {
                return $return_product->return_number ?? '-';
            })
            ->addColumn('transaction_id', function ($return_product) {
                return $return_product->transaction_id ?? '-';
            })
            ->addColumn('product_received_remark', function ($return_product) {
                return $return_product->product_received_remark ?? '-';
            })
            ->addColumn('settlement_date', function ($return_product) {
                return $return_product->settlement_date
                    ? toIndianDate($return_product->settlement_date)
                    : '-';
            })

            ->editColumn('return_product_name', function ($return_product) {
                $productName = e($return_product->orderProduct->product->name);
                $propertyValues = e($return_product->orderProduct->property_value_names);
                $html = '<div>';
                $html .= "<span>{$productName} ({$propertyValues})</span><br>";

                return $html;
            })
            ->addColumn('return_quantity', function ($return_product) {
                return $return_product->return_quantity;
            })

            ->addColumn('return_price', function ($return_product) {
                return toCurrency($return_product->orderProduct->price * $return_product->return_quantity, $return_product->order->currency_code);
            })
            ->addColumn('return_number', function ($return_product) {
                return $return_product->return_number ?? '-';
            })
            ->addColumn('product_model', function ($return_product) {
                $return_Products = $return_product->orderProduct;

                if (!$return_Products) return '-';

                $productPrice = ProductPrice::where('product_id', $return_Products->product_id)
                    ->whereJsonContains('property_values', array_map('intval', $return_Products->property_values ?? []))
                    ->first();

                return $productPrice->model ?? '-';
            })

            ->addColumn('transaction_id', function ($return_product) {
                return $return_product->transaction_id ?? '-';
            })
            ->addColumn('remark', function ($return_product) {
                return $return_product->remark ?? '-';
            })
            ->editColumn('status', function ($return_product) {
                // Define the mapping of statuses to badge colors and display text
                $statusMap = [
                    'RETURN_IN_PROGRESS' => ['color' => 'danger', 'label' => 'Return in progress'],
                    'PRODUCT_RECEIVED' => ['color' => 'info', 'label' => 'Product received'],
                    'REFUND_INITIATE' => ['color' => 'warning', 'label' => 'Refund initiate'],
                    'REFUND_COMPLETED' => ['color' => 'success', 'label' => 'Refund completed'],
                    'REFUND_PROCESSED' => ['color' => 'secondary', 'label' => 'Refund processced'],
                ];

                // Use uppercase status keys internally
                $statusKey = strtoupper($return_product->status);

                // Fallback if status not found
                $badgeColor = $statusMap[$statusKey]['color'] ?? 'secondary';
                $label = $statusMap[$statusKey]['label'] ?? $return_product->status;

                // For REFUND_COMPLETED, show static badge (not clickable)
                if ($statusKey === 'REFUND_COMPLETED') {
                    return '<span class="badge bg-' . $badgeColor . ' fs-1">' . $label . '</span>';
                }

                return '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 return-product-status-switch"
                data-bs-toggle="modal"
                data-bs-target="#statusChangeModal"
                data-routekey="' . $return_product->route_key . '"
                data-id="' . $return_product->id . '"
                data-status="' . $statusKey . '"
                data-transaction-id="' . ($return_product->transaction_id ?? '') . '"
                data-settlement-date="' . ($return_product->settlement_date ?? '') . '"
                data-product-received-remark="' . ($return_product->product_received_remark ?? '') . '">
                <i class="ti ti-analyze"></i> &nbsp; ' . $label . '</button>';
            })

            ->addColumn('action', function ($return_product) {
                $show = '<a href="' . route('admin.return-products.show', $return_product->id) . '" 
                    class="badge bg-info fs-1 modal-one-btn" 
                    data-entity="return-products"  
                    data-title="Status Logs" 
                    data-route-key="' . $return_product->route_key . '">
                    <i class="fa fa-eye"></i></a>';
                return $show;
            })

            ->addIndexColumn()
            ->rawColumns(['order_number', 'return_number', 'transaction_id', 'settlement_date', 'product_received_remark', 'customer', 'return_product_name', 'product_model', 'return_quantity', 'return_price', 'status', 'created_at', 'remark', 'action'])
            ->setRowId('id')
            ->make(true);
    }

    public function create()
    {
        $orders = Order::all();
        return view('Admin.ReturnProducts.form', compact('orders'));
    }


    public function show(ReturnProduct $return_product)
    {
        $status_logs = $return_product->statusLogs()->get();

        return view('Admin.ReturnProducts.show', compact('return_product', 'status_logs'));
    }

    public function orderProduct(Request $request)
    {
        $orderId = $request->order_id;

        $orderProducts = OrderProduct::where('order_id', $orderId)->get();

        foreach ($orderProducts as $product) {
            $returnedQty = ReturnProduct::where('order_id', $orderId)
                ->where('order_product_id', $product->id)
                ->sum('return_quantity');

            $product->remaining_quantity = $product->quantity - $returnedQty;
        }

        $orderProducts = $orderProducts->filter(function ($product) {
            return $product->remaining_quantity > 0;
        });
        return view('Admin.ReturnProducts.order-product', compact('orderProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'return_quantities' => [
                'required',
                'array',
                function ($attribute, $values, $fail) {
                    if (!collect($values)->filter(fn($quantity) => (int) $quantity > 0)->count()) {
                        $fail('At least one product return quantity is required.');
                    }
                }
            ],
        ], [
            'order_id.required' => 'Order ID is required.',
            'return_quantities.required' => 'Return quantities are required.',
        ]);

        $order_id = $request->order_id;
        $return_quantities = $request->return_quantities;
        $remarks = $request->input('remarks', []);

        foreach ($return_quantities as $product_id => $return_quantity) {
            if ($return_quantity <= 0) {
                continue;
            }

            $order_prooduct = OrderProduct::where('order_id', $order_id)
                ->where('id', $product_id)
                ->first();

            if (!$order_prooduct) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Product not found in the order."
                ], 400);
            }

            $existing_return = ReturnProduct::where('order_id', $order_id)
                ->where('order_product_id', $product_id)
                ->first();

            $already_returned_quantity = $existing_return ? $existing_return->return_quantity : 0;
            $remainingQty = $order_prooduct->quantity - $already_returned_quantity;

            if ($return_quantity > $remainingQty) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Return quantity for product cannot be more than available quantity."
                ], 400);
            }

            $remark = $remarks[$product_id] ?? '';

            if ($existing_return) {
                // Increment return quantity and update remark
                $existing_return->return_quantity += $return_quantity;
                if ($remark) {
                    $existing_return->remark = trim($existing_return->remark . ' ' . $remark);
                }
                $existing_return->save();
            } else {
                $fixedPrefix = 'R543210+';

                // Get last return number with that prefix
                $last_return_number = ReturnProduct::whereNotNull('return_number')
                    ->where('return_number', 'like', $fixedPrefix . '%')
                    ->orderBy('id', 'desc')
                    ->value('return_number');

                // Extract last sequence number
                $last_number = 0;
                if ($last_return_number && preg_match('/\+(\d+)$/', $last_return_number, $matches)) {
                    $last_number = (int) $matches[1];
                }

                // Generate next return number
                $next_number = $fixedPrefix . str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);

                $return_product = ReturnProduct::create([
                    'order_id' => $order_id,
                    'order_product_id' => $product_id,
                    'return_quantity' => $return_quantity,
                    'remark' => $remark,
                    'return_number' => $next_number,
                ]);

                ReturnStatusLog::create([
                    'return_product_id' => $return_product->id,
                    'status' => 'RETURN_IN_PROGRESS',
                ]);

                // Send Email Notification
                $emailData = [
                    'subject' => 'Return Initiated - #' . $return_product->order->order_number,
                    'order_number' => $return_product->order->order_number,
                    'customer_name' => $return_product->order->user->full_name,
                    'product_name' => $return_product->orderProduct->product->name,
                    'quantity' => $return_product->return_quantity,
                    'remark' => $return_product->remark,
                ];

                EmailService::sendEmail($return_product->order->user->email, 'emails.order-return', $emailData);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product return created successfully.',
        ], 201);
    }

    public function updateStatus(Request $request)
    {

        $request->validate(
            [
                'status' => 'required',
                'transaction_id' => 'required_if:status,REFUND_PROCESSED',
                'settlement_date' => 'required_if:status,REFUND_PROCESSED',
                'product_received_remark' => 'required_if:status,PRODUCT_RECEIVED',
            ],
            [
                'status.required' => 'Status is required.',
                'transaction_id.required_if' => 'Transaction ID is required.',
                'settlement_date.required_if' => 'Settlement date is required.',
                'product_received_remark.required_if' => 'Remark is required when product is received.',
            ]
        );

        $return_product = ReturnProduct::find($request->id);
        $return_product->status = $request->status;

        $order = $return_product->order;

        $email_view = null;
        $email_data = null;

        if ($request->status === 'PRODUCT_RECEIVED') {
            $return_product->product_received_remark = $request->product_received_remark;
        }

        if ($request->status === 'REFUND_PROCESSED') {
            $return_product->transaction_id = $request->transaction_id;
            $return_product->settlement_date = $request->settlement_date;

            $refund_amount = $return_product->orderProduct->price * $return_product->return_quantity;

            $email_view = 'emails.refund-processed';
            $email_data = [
                'subject' => 'Refund Processed - #' . $order->order_number,
                'order_number' => $order->order_number,
                'customer_name' => $order->user->full_name,
                'product_name' => $return_product->orderProduct->product->name,
                'quantity' => $return_product->return_quantity,
                'refund_amount' => $refund_amount,
            ];

            EmailService::sendEmail($order->user->email, $email_view, $email_data);

            $variable = $order->order_number . '|' . config('app.url');

            $sms_template_id = 188126;

            send_sms($order->user->mobile, $variable, $sms_template_id);
        }

        if ($request->status === 'REFUND_COMPLETED') {

            $refund_amount = $return_product->orderProduct->price * $return_product->return_quantity;

            $email_view = 'emails.refund-completed';
            $email_data = [
                'subject' => 'Refund Completed - #' . $order->order_number,
                'order_number' => $order->order_number,
                'customer_name' => $order->user->full_name,
                'product_name' => $return_product->orderProduct->product->name,
                'quantity' => $return_product->return_quantity,
                'refund_amount' => $refund_amount,
            ];

            EmailService::sendEmail($order->user->email, $email_view, $email_data);

            $variable = $order->order_number . '|' . config('app.url');

            $sms_template_id = 188125;

            send_sms($order->user->mobile, $variable, $sms_template_id);
        }

        $return_product->save();

        ReturnStatusLog::create([
            'return_product_id' => $return_product->id,
            'status' => $request->status,
            'product_received_remark' => $request->product_received_remark,
            'transaction_id' => $request->transaction_id,
            'settlement_date' => $request->settlement_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product status changed successfully'
        ], 201);
    }

    public function export(Request $request)
    {
        return Excel::download(new ReturnProductExport($request), 'return-products.xlsx');
    }
}
