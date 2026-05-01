<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Wishlist;
use App\Models\CouponCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function myWishlists()
    {
        $user = Auth::user();
        $wishlists = collect();

        if ($user) {
            $wishlists = Wishlist::where('user_id', $user->id)->get();
        }

        return view('Frontend.Pages.wishlist', compact('wishlists'));
    }

    public function toggleWishlist(Request $request)
    {
        $user = Auth::user();

        $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from wishlist.',
            ], 200);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product added in wishlist.',
            ], 201);
        }
    }

    public function getWishlistCount()
    {
        $user = Auth::user();

        $wishlist_count = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'status' => 'success',
            'wishlist_count' => $wishlist_count
        ], 200);
    }
}
