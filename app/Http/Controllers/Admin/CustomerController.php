<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Razorpay\Api\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('type', 'CUSTOMER')->get();

        return view('Admin.Customers.index', compact('customers'));
    }

    public function data(Request $request)
    {
        $query = User::where('type', 'CUSTOMER')->orderByDesc('id');


        if ($request->from_date && $request->to_date) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $to = Carbon::parse($request->to_date)->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        return DataTables::eloquent($query)
            ->editColumn('full_name', function ($user) {
                return $user->full_name;
            })
            ->editColumn('email', function ($user) {
                return $user->email;
            })
            ->editColumn('mobile', function ($user) {
                return $user->mobile;
            })
            ->editColumn('created_at', function ($user) {
                return toIndianDate($user->created_at);
            })
            ->editColumn('status', function ($user) {
                if ($user->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input user-status-switch" type="checkbox" checked data-routekey="' . $user->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input user-status-switch" type="checkbox" data-routekey="' . $user->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($user) {
                return '<a href="' . route('admin.customers.profile', $user->route_key) . '" class="btn btn-sm btn-primary"><i class="ti ti-eye"></i></a>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'first_name', 'last_name', 'email', 'mobile', 'gender', 'created_at'])
            ->setRowId('id')
            ->make(true);
    }

    public function profile(User $user)
    {
        $total_orders = $user->orders->count();
        $total_order_amount = $user->orders->sum('total_amount');
        $total_cart_products = $user->carts->count();
        $total_wishlist_products = $user->wishlists->count();

        return view('Admin.Customers.profile', compact('user', 'total_orders', 'total_order_amount', 'total_cart_products', 'total_wishlist_products'));
    }

    public function export(Request $request)
    {
        return Excel::download(new CustomerExport($request), 'inactiveProducts.xlsx');
    }
}
