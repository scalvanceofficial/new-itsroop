<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Order::with(['user.addresses', 'products.product', 'coupon_code']);

        if ($this->request->from_date && $this->request->to_date) {
            $query->whereBetween('created_at', [$this->request->from_date, $this->request->to_date]);
        }

        $orders = $query->get();


        $exportData = [];

        foreach ($orders as $order) {
            $address = $order->user->addresses->where('default', 'YES')->first();

            foreach ($order->products as $p) {
                $discountPrice = $p->product?->getPrice($p->property_values)?->discount_price ?? 0;
                $discountTotal = $discountPrice * $p->quantity;

                $exportData[] = [
                    $order->user->full_name,
                    $order->user->email,
                    $order->user->mobile,
                    $address?->type ?? '',
                    $address?->address_line_1 ?? '',
                    $address?->address_line_2 ?? '',
                    $address?->city ?? '',
                    $address?->pincode ?? '',
                    $order->order_number,
                    $order->razorpay_order_id,
                    $order->razorpay_payment_id,
                    $order->shiprocket_order_id,
                    $order->shiprocket_shipment_id,
                    $order->awb_code,
                    $order->status,
                    $order->shiprocket_status,
                    $order->total_amount,
                    $order->coupon_code?->coupon_code ?? '',
                    $p->product?->name ?? '',
                    $p->property_value_names,
                    $p->quantity,
                    $discountTotal,
                    $p->price,
                    $p->total_amount,

                ];
            }
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Mobile',
            'Address Type',
            'Address Line 1',
            'Address Line 2',
            'City',
            'Pincode',
            'Order Number',
            'Razorpay Order ID',
            'Razorpay Payment ID',
            'Shiprocket Order ID',
            'Shiprocket Shipment ID',
            'AWB Code',
            'Status',
            'Shiprocket Status',
            'Total Amount',
            'Coupon Code',
            'Product Name',
            'Property Names',
            'Quantity',
            'Discount Price',
            'Price',
            'Total',
        ];
    }
}
