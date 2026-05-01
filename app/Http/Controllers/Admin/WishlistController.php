<?php

namespace App\Http\Controllers\Admin;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Exports\WishlistExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class WishlistController extends Controller
{
    public function index()
    {
        return view('Admin.Wishlists.index');
    }

    public function data(Request $request)
    {
        $query = Wishlist::select('user_id')
            ->with('user')
            ->whereHas('user', function ($q) {
                $q->where('type', 'CUSTOMER');
            })
            ->when($request->user_id, function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->groupBy('user_id');


        return DataTables::eloquent($query)
            ->addColumn('customer', function ($wishlist) {
                return $wishlist->user->full_name;
            })
            ->editColumn('products', function ($wishlist) {
                $wishlists = Wishlist::where('user_id', $wishlist->user_id)
                    ->with('product')
                    ->get();

                $html = '<div style="overflow-y: auto; max-height: 80px;">';
                $html .= '<ul class="list-unstyled mb-0">';
                $index = 1;
                foreach ($wishlists as $wishlist) {
                    $html .= '<li class="d-flex justify-content-between align-items-center mt-2">';
                    $html .= '<b>' . e($index++ . '. ' . $wishlist->product->name) . '</b>';
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
        return Excel::download(new WishlistExport($request), 'wishlist-products.xlsx');
    }
}
