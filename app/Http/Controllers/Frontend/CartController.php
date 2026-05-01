<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $carts = collect();

        if ($user) {
            $carts = Cart::where('user_id', $user->id)->get();
        }

        return view('Frontend.Pages.cart', compact('carts'));
    }

    public function addCartProduct(Request $request)
    {
        $user = Auth::user();

        $query = ProductPrice::where('product_id', $request->product_id);

        foreach ($request->property_values as $value) {
            $query->whereJsonContains('property_values', (int)$value);
        }

        $productPrice = $query->first();

        if (!$productPrice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product with selected property values not found.'
            ], 404);
        }

        if ($productPrice->stock == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'This product is out of stock and cannot be added to the cart.'
            ], 400);
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->whereJsonContains('property_values', $request->property_values)
            ->first();


        $existingQty = $cart ? $cart->quantity : 0;
        $totalQty = $existingQty + ($request->quantity ?? 1);

        if ($totalQty > $productPrice->stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only ' . $productPrice->stock . ' items in stock.'
            ], 400);
        }

        if ($cart) {
            $cart->update(['quantity' => $totalQty]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'property_values' => $request->property_values,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart.'
        ], 201);
    }

    public function removeCartProduct(Request $request)
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->whereJsonContains('property_values', $request->property_values)
            ->first();

        if ($cart) {
            $cart->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from cart.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found in cart.'
            ], 400);
        }
    }

    public function updateCartProduct(Request $request)
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->whereJsonContains('property_values', $request->property_values)
            ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $request->quantity
            ]);

            $total_amount = 0;
            $carts = Cart::where('user_id', $user->id)->get();

            foreach ($carts as $cart) {
                $cart_data = getCartData($cart);
                $total_amount += $cart_data['price'] * $cart->quantity;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Cart product updated.',
                'total_amount' => toCurrency($total_amount)
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found in cart.'
            ], 400);
        }
    }

    public function getCartCount()
    {
        $user = Auth::user();

        $cart_count = Cart::where('user_id', $user->id)->count();

        return response()->json([
            'status' => 'success',
            'cart_count' => $cart_count
        ], 200);
    }
}
