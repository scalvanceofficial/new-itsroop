<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Enquiry;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Wishlist;
use App\Models\Subscriber;
use Razorpay\Api\Customer;
use App\Models\Testimonial;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\PropertyValue;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $current_month = Carbon::now()->month;
        $current_year = Carbon::now()->year;
        $total_users = User::count(); // Use this if you want customer count
        $total_orders  = Order::count();
        $total_products = Product::count();
        $total_enquiries  = Enquiry::count();
        $total_categories = Category::count();
        $total_reviews = Review::count();
        $total_testimonial = Testimonial::count();
        $total_subscribers = Subscriber::count();
        $total_cart_products = Cart::count();

        return view('Admin.Dashboard.index', compact(
            'current_month',
            'current_year',
            'total_users',
            'total_orders',
            'total_products',
            'total_enquiries',
            'total_categories',
            'total_reviews',
            'total_testimonial',
            'total_subscribers',
            'total_cart_products'
        ));
    }


    public function getData(Request $request)
    {
        // Base query
        $order_query = Order::query();

        if ($request->month) {
            $order_query->whereMonth('created_at', $request->month);
        }

        if ($request->year) {
            $order_query->whereYear('created_at', $request->year);
        }

        // Clone the base query for reuse
        $baseQuery = clone $order_query;

        // Main totals
        $total_orders = $order_query->count();
        $total_amount = $order_query->sum('total_amount');

        $total_new_orders = (clone $baseQuery)->where('shiprocket_status', 'NEW')->count();
        $total_shipped_orders = (clone $baseQuery)->where('shiprocket_status', 'SHIPPED')->count();
        $total_delivered_orders = (clone $baseQuery)->where('shiprocket_status', 'DELIVERED')->count();
        $total_cancelled_orders = (clone $baseQuery)->where('shiprocket_status', 'CANCELLED')->count();

        // Status-wise amounts
        $total_new_order_amount = (clone $baseQuery)->where('shiprocket_status', 'NEW')->sum('total_amount');
        $total_shipped_order_amount = (clone $baseQuery)->where('shiprocket_status', 'SHIPPED')->sum('total_amount');
        $total_delivered_order_amount = (clone $baseQuery)->where('shiprocket_status', 'DELIVERED')->sum('total_amount');
        $total_cancelled_order_amount = (clone $baseQuery)->where('shiprocket_status', 'CANCELLED')->sum('total_amount');

        // Other statuses
        $excluded_statuses = ['NEW', 'DISPATCHED', 'DELIVERED', 'CANCELLED'];
        $total_other_orders = (clone $baseQuery)->whereNotIn('shiprocket_status', $excluded_statuses)->count();
        $total_other_order_amount = (clone $baseQuery)->whereNotIn('shiprocket_status', $excluded_statuses)->sum('total_amount');


        // Currency-wise totals
        $currency_distribution = (clone $baseQuery)
            ->selectRaw('currency_code, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('currency_code')
            ->get()
            ->map(function ($item) {
                return [
                    'code' => $item->currency_code,
                    'count' => $item->count,
                    'total' => toCurrency($item->total, $item->currency_code)
                ];
            });

        $most_used_currency = $currency_distribution->sortByDesc('count')->first()['code'] ?? 'N/A';

        // Prepare response
        $data = [
            'total_orders' => formatNumber($total_orders),
            'total_amount' => toCurrency($total_amount, 'GBP'),
            'most_used_currency' => $most_used_currency,
            'currency_distribution' => $currency_distribution,

            'total_new_orders' => formatNumber($total_new_orders),
            'total_shipped_orders' => formatNumber($total_shipped_orders),
            'total_delivered_orders' => formatNumber($total_delivered_orders),
            'total_cancelled_orders' => formatNumber($total_cancelled_orders),
            'total_other_orders' => formatNumber($total_other_orders),

            'total_new_order_amount' => toCurrency($total_new_order_amount, 'GBP'),
            'total_shipped_order_amount' => toCurrency($total_shipped_order_amount, 'GBP'),
            'total_delivered_order_amount' => toCurrency($total_delivered_order_amount, 'GBP'),
            'total_cancelled_order_amount' => toCurrency($total_cancelled_order_amount, 'GBP'),
            'total_other_order_amount' => toCurrency($total_other_order_amount, 'GBP'),
        ];

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function getWishlistProduct(Request $request)
    {
        $wishlist_product_query = Wishlist::query();

        if ($request->month) {
            $wishlist_product_query->whereMonth('wishlists.created_at', $request->month);
        }

        if ($request->year) {
            $wishlist_product_query->whereYear('wishlists.created_at', $request->year);
        }

        $wishlist_products = $wishlist_product_query->selectRaw(
            'products.id as product_id,
            products.name as product_name,
            COUNT(*) as total_views'
        )
            ->join('products', 'products.id', '=', 'wishlists.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get();


        return view('Admin.Dashboard.wishlist-products', compact('wishlist_products'));
    }


    public function getSellingProduct(Request $request)
    {
        $selling_product_query = OrderProduct::query();

        if ($request->month) {
            $selling_product_query->whereMonth('order_products.created_at', $request->month);
        }

        if ($request->year) {
            $selling_product_query->whereYear('order_products.created_at', $request->year);
        }

        $selling_products = $selling_product_query->selectRaw('
        products.id as product_id,
        products.name as product_name,
        order_products.property_values,
        COUNT(*) as total_views
    ')
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->groupBy('products.id', 'products.name', 'order_products.property_values')
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get();

        // Parse property values
        foreach ($selling_products as $product) {
            $product_data = getCartData($product); // Assumes it returns an array with property info
            $product->property_name = $product_data['property_values']; // Show human-readable property values
        }

        return view('Admin.Dashboard.selling-product', compact('selling_products'));
    }


    public function getOrderSummary(Request $request)
    {
        $months = get_months();
        $monthly_total_orders = [];
        $monthly_total_amounts = [];

        $orders = Order::selectRaw(
            'COUNT(*) as total_orders,
            MONTH(created_at) as month,
            SUM(total_amount) as total_amount'
        )
            ->whereYear('created_at', $request->year)
            ->groupBy('month')
            ->get();

        foreach ($months as $month_number => $month) {
            $order = $orders->where('month', $month_number)->first();
            $monthly_total_orders[] = $order ? formatNumber($order->total_orders) : 0;
            $monthly_total_amounts[] = $order ? toCurrency($order->total_amount, 'GBP') : toCurrency(0, 'GBP');
        }

        $data = [
            'monthly_total_orders' => $monthly_total_orders,
            'monthly_total_amounts' => $monthly_total_amounts
        ];

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
}
