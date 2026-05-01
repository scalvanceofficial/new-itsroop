<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use App\Exports\CartExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CartController extends Controller
{
    // Display orders index page
    public function index()
    {
        return view('Admin.Cart.index');
    }

    // Fetch data for DataTable
    public function data(Request $request)
    {
        $query = Cart::select('user_id')
            ->with('user')
            ->whereHas('user', function ($q) {
                $q->where('type', 'CUSTOMER');
            })
            ->when($request->user_id, function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->groupBy('user_id');


        return DataTables::eloquent($query)
            ->addColumn('customer', function ($cart) {
                return $cart->user->full_name;
            })
            ->editColumn('products', function ($cart) {
                $carts = Cart::where('user_id', $cart->user_id)
                    ->with('product')
                    ->get();

                $html = '<div style="overflow-y: auto; max-height: 150px;">';
                $html .= '<ul class="list-unstyled mb-0">';

                foreach ($carts as $cart_item) {
                    $cart_data = getCartData($cart_item);

                    $html .= '<li class="d-flex align-items-center border-bottom py-2">';
                    $html .= '<img src="' . e($cart_data['image']) . '" alt="' . e($cart_item->product->name) . '" class="" style="width: 60px; height: 60px;">';

                    $html .= '<div class="ms-3 flex-grow-1">';
                    $html .= '<span class="d-block text-dark fw-bold" style="font-size: 14px;">' . e($cart_item->product->name) . '</span>';
                    $html .= '<small class="text-muted" style="font-size: 12px;">' . e($cart_data['property_values']) . '</small><br>';

                    $html .= '<div class="d-flex justify-content-between align-items-center mt-1">';
                    $html .= '<span class="badge bg-primary text-white" style="font-size: 12px;">Qty: ' . e($cart_item->quantity) . '</span>';
                    $html .= '<span class="text-success fw-bold" style="font-size: 14px;">' . toIndianCurrency($cart_data['price'] * $cart_item->quantity) . '</span>';
                    $html .= '</div>';
                    $html .= '</div>';

                    $html .= '</li>';
                }

                $html .= '</ul>';
                $html .= '</div>';

                return $html;
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $search_value = $request->search['value'];
                    $query->whereHas('user', function ($q) use ($search_value) {
                        $q->where('first_name', 'like', "%{$search_value}%")
                            ->orWhere('last_name', 'like', "%{$search_value}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search_value}%"]);
                    });
                }
            })
            ->addIndexColumn()
            ->rawColumns(['customer', 'products'])
            ->setRowId('user_id')
            ->make(true);
    }

    public function export(Request $request)
    {
        return Excel::download(new CartExport($request), 'cart-products.xlsx');
    }
}
