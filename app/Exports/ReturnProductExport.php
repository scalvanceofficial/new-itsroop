<?php

namespace App\Exports;

use App\Models\ReturnProduct;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReturnProductExport implements FromArray, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        $query = ReturnProduct::query();

        if ($this->request->from_date && $this->request->to_date) {
            $query->whereBetween('created_at', [
                $this->request->from_date,
                $this->request->to_date
            ]);
        }

        $return_products = $query->orderByDesc('id')->get();

        $data = [];

        foreach ($return_products as $return_product) {
            $data[] = [
                $return_product->order->user->full_name,
                $return_product->return_number,
                $return_product->order->order_number,
                $return_product->orderProduct->product->name,
                $return_product->orderProduct->property_value_names ?? 'N/A',
                $return_product->return_quantity,
                $return_product->orderProduct->price * $return_product->return_quantity,
                $return_product->status,
                toIndianDate($return_product->settlement_date),
                $return_product->transaction_id,
                $return_product->remark,
                toIndianDate($return_product->created_at),

            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'Return Number',
            'Order Number',
            'Product Name',
            'Property Value Names',
            'Return Quantity',
            'Total Amount',
            'Status',
            'Settlement Date',
            'Transaction ID',
            'Remark',
            'Created At',
        ];
    }
}
