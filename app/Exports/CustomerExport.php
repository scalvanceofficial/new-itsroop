<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromArray, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        $query = User::where('type', 'CUSTOMER');

        if ($this->request->from_date && $this->request->to_date) {
            $query->whereBetween('created_at', [
                $this->request->from_date,
                $this->request->to_date
            ]);
        }

        $customers = $query->orderByDesc('id')->get();

        $customer_array = [];

        foreach ($customers as $customer) {
            $orders = $customer->orders();
            $total_order_amount = $orders->sum('total_amount');
            $total_orders = $orders->count();
            $address = $customer->addresses()->where('default', 'YES')->first();

            $customer_array[] = [
                $customer->full_name,
                $customer->email,
                $customer->mobile,
                $address ? $address->type : '',
                $address ? $address->address_line_1 : '',
                $address ? $address->address_line_2 : '',
                $address ? $address->city : '',
                $address ? $address->pincode : '',
                $total_orders,
                $total_order_amount,
            ];
        }

        return $customer_array;
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
            'Total Orders',
            'Total Order Amount',
        ];
    }
}
