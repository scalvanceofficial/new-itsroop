<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\Property;
use App\Models\CouponCode;
use App\Models\SubCategory;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductPropertyValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Detect gender from URL segment (men/women/unisex routes)
        $gender_map = ['men' => 'Men', 'women' => 'Women', 'unisex' => 'Unisex'];
        $slug       = $request->category_slug ?? request()->segment(2);
        $selected_gender        = isset($gender_map[$slug]) ? $gender_map[$slug] : null;
        $selected_category_slug = $selected_gender ? null : $slug;
        $category = null;

        if ($selected_category_slug) {
            $category = Category::where('slug', $selected_category_slug)->first();
        }

        $product_categories = Category::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        $properties = Property::where('status', 'ACTIVE')->get();

        if ($category) {
            $subCategories = SubCategory::where('status', 'ACTIVE')
                ->where('category_id', $category->id)
                ->take(5)
                ->get();
        } else {
            $subCategories = SubCategory::where('status', 'ACTIVE')->take(5)->get();
        }

        $products = Product::where('status', 'ACTIVE')
            ->when($category, function ($query, $category) {
                return $query->whereJsonContains('category_ids', (string) $category->id);
            })
            ->when($selected_gender, function ($query, $selected_gender) {
                return $query->where(function ($q) use ($selected_gender) {
                    $q->where('gender', $selected_gender)
                      ->orWhere('gender', 'Unisex');
                });
            })
            ->when($request->search, function ($query) use ($request) {
                $search = strtolower(trim($request->search));

                return $query->where(function ($q) use ($search) {
                    $q->whereRaw('SOUNDEX(name) = SOUNDEX(?)', [$search])
                        ->orWhereRaw('SOUNDEX(keywords) = SOUNDEX(?)', [$search])
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('keywords', 'LIKE', "%$search%");
                    $q->orWhereHas('productPrices', function ($subQ) use ($search) {
                        $subQ->where('model', 'LIKE', "%$search%");
                    });
                });
            })
            ->when($request->sub_category, function ($query) use ($request) {
                $sub_category = SubCategory::where('name', $request->sub_category)->first();
                return $query->whereJsonContains('sub_category_ids', (string) $sub_category->id);
            })
            ->get();

        return view('Frontend.Products.products', compact(
            'products',
            'subCategories',
            'properties',
            'product_categories',
            'selected_category_slug',
            'selected_gender',
        ));
    }

    public function productDetails($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();

        $product_property_labels = ProductPropertyValue::where('product_id', $product->id)
            ->join('properties', 'product_property_values.property_id', '=', 'properties.id')
            ->select('properties.label')
            ->distinct()
            ->pluck('label');

        $primary_property_value_ids = ProductPropertyValue::where('product_id', $product->id)
            ->where('is_primary', 'YES')
            ->pluck('property_value_id');

        $product_images = $product->productImages;

        $product_review = Review::where('product_id', $product->id)
            ->selectRaw('
            COUNT(*) as total_reviews,
            ROUND(AVG(rating), 1) as average_rating,
            SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star_count,
            SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star_count,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star_count,
            SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star_count,
            SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star_count
        ')
            ->first();

        $people_also_bought = Product::where('status', 'ACTIVE')
            ->where('id', '!=', $product->id)
            ->inRandomOrder()->take(4)->get();

        $recently_viewed_ids = session()->get('recently_viewed', []);
        $recently_viewed = Product::whereIn('id', $recently_viewed_ids)
            ->where('id', '!=', $product->id)
            ->take(4)->get();
            
        if (!in_array($product->id, $recently_viewed_ids)) {
            array_unshift($recently_viewed_ids, $product->id);
            session()->put('recently_viewed', array_slice($recently_viewed_ids, 0, 10));
        }

        return view('Frontend.Products.product-details', compact('product', 'product_property_labels', 'product_review', 'product_images', 'people_also_bought', 'recently_viewed'));
    }

    public function getProductImages(Request $request, Product $product)
    {
        $product_images = collect();

        if ($request->property_values) {
            foreach ($request->property_values as $property_value) {
                if ($property_value['is_image_property'] == 'YES') {
                    $product_images = ProductImage::where('product_id', $product->id)
                        ->where('property_value_id', $property_value['property_value_id'])
                        ->pluck('image')
                        ->map(function ($image) {
                            return Storage::url($image); // Prepend the storage URL
                        });
                }
            }
        }

        return response()->json(['product_images' => $product_images], 200);
    }

    public function getProductPrice(Request $request, Product $product)
    {
        $property_value_id = (int) ($request->property_values[0]['property_value_id'] ?? 0);

        $product_price = ProductPrice::where('product_id', $product->id)
            ->whereJsonContains('property_values', $property_value_id)
            ->first();

        if (!$product_price) {
             return response()->json(['error' => 'Price not found'], 404);
        }

        return response()->json([
            'product_price' => [
                'selling_price' => toCurrency($product_price->selling_price),
                'actual_price'  => toCurrency($product_price->actual_price),
                'selling_price_raw' => $product_price->selling_price,
                'discount_percentage' => $product_price->discount_percentage,
                'stock' => $product_price->stock,
            ]
        ], 200);
    }

    public function getFilteredProducts(Request $request)
    {
        $productsQuery = Product::where('status', 'ACTIVE');

        // ✅ CATEGORY
        $category = Category::where('slug', $request->category_slug)->first();

        if ($category) {
            $productsQuery->whereJsonContains('category_ids', (string) $category->id);

            $subCategories = SubCategory::where('category_id', $category->id)
                ->where('status', 'ACTIVE')
                ->take(5)
                ->get();
        } else {
            $subCategories = SubCategory::where('status', 'ACTIVE')->take(5)->get();
        }

        // ✅ SUB CATEGORY FILTER
        if ($request->sub_category) {
            $sub_category = SubCategory::where('name', $request->sub_category)->first();
            if ($sub_category) {
                $productsQuery->whereJsonContains('sub_category_ids', (string) $sub_category->id);
            }
        }

        // ✅ GENDER FILTER
        if ($request->gender) {
            $productsQuery->where(function ($q) use ($request) {
                $q->where('gender', $request->gender)
                  ->orWhere('gender', 'Unisex');
            });
        }

        // ✅ PROPERTY VALUES FILTER
        $property_values = $request->property_values ?? [];

        if (!empty($property_values)) {
            $productsQuery->whereIn('id', function ($query) use ($property_values) {
                $query->select('product_id')
                    ->from('product_property_values')
                    ->whereIn('property_value_id', $property_values)
                    ->groupBy('product_id')
                    ->havingRaw('COUNT(DISTINCT property_value_id) = ?', [count($property_values)]);
            });
        }

        // ✅ SEARCH FILTER
        if ($request->search) {
            $search = strtolower(trim($request->search));
            $productsQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('keywords', 'LIKE', "%$search%");
                $q->orWhereHas('productPrices', function ($subQ) use ($search) {
                    $subQ->where('model', 'LIKE', "%$search%");
                });
            });
        }

        // ✅ FETCH PRODUCTS
        $products = $productsQuery->with('productPrices')->get();

        // ✅ STOCK FILTER
        if ($request->stock_sort) {
            if ($request->stock_sort === 'available') {
                $products = $products->filter(fn($product) => !$product->isOutOfStock());
            } elseif ($request->stock_sort === 'out_of_stock') {
                $products = $products->filter(fn($product) => $product->isOutOfStock());
            }
        }

        // ✅ RATING SORT
        if ($request->rating_sort) {
            $products = $products->sortBy(function ($product) {
                return $product->average_rating ?? 0;
            });

            if ($request->rating_sort == 'high_to_low') {
                $products = $products->reverse();
            }
        }

        // ✅ PRICE SORT
        if ($request->price_sort) {
            $products = $products->sortBy(function ($product) {
                return $product->getPrice()->selling_price ?? 0;
            });

            if ($request->price_sort === 'high_to_low') {
                $products = $products->reverse();
            }
        }

        return view('Frontend.Products.filtered-products', compact(
            'products',
            'property_values',
            'subCategories',
            'request' // optional if you want to keep selected filters
        ));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'required',
            // 'description' => 'required',
            // 'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Optional images
        ]);

        $photoPaths = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reviews/photos', 'public');
                $photoPaths[] = $path;
            }
        }

        Review::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'description' => $request->description,
            'photos' => $photoPaths,
        ]);

        $averageRating = Review::where('product_id', $request->product_id)->avg('rating');

        $product = Product::find($request->product_id);
        $product->average_rating = $averageRating;
        $product->save();



        return response()->json([
            'status' => 'success',
            'message' => 'Review submitted successfully.',
        ], 201);
    }

    public function applyCouponCode(Request $request)
    {
        $couponCode = $request->input('coupon');
        $totalAmount = $request->input('totalSellingPrice');

        // Validate coupon
        $coupon = CouponCode::where('coupon_code', $couponCode)
            ->where('status', 'ACTIVE')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Invalid or expired coupon code'], 400);
        }

        $user = Auth::user();

        $alreadyUsed = Order::where('user_id', $user->id)
            ->where('coupon_code_id', $coupon->id)
            ->exists();

        if ($alreadyUsed) {
            return response()->json([
                'error' => 'You have already used this coupon code.'
            ], 400);
        }

        $currencyCode = session('currency', 'GBP');
        if ($coupon->currency_code !== $currencyCode) {
            return response()->json(['error' => 'This coupon is not valid for ' . $currencyCode], 400);
        }

        $totalInCouponCurrency = convertToCurrency($totalAmount, $coupon->currency_code);

        if ($totalInCouponCurrency < $coupon->minimum_order_amount) {
            return response()->json(['error' => 'Minimum order value should be ' . formatCurrency($coupon->minimum_order_amount, $coupon->currency_code)], 400);
        }

        $discountPercentage = $coupon->percentage ?? 0;
        $discountAmount = ($totalAmount * $discountPercentage) / 100;
        $finalAmount = $totalAmount - $discountAmount;

        return response()->json([
            'status' => 'success',
            'coupon_code_id' => $coupon->id,
            'total_amount' => $totalAmount,
            'final_price' => $finalAmount,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'formatted_discount_amount' => toCurrency($discountAmount, $currencyCode),
            'formatted_final_price' => toCurrency($finalAmount, $currencyCode),
        ]);
    }

    public function searchRecommendations(Request $request)
    {
        $search = strtolower(trim($request->q));

        if (empty($search)) {
            return '';
        }

        $products = Product::where('status', 'ACTIVE')
            ->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('keywords', 'LIKE', "%$search%");
                $q->orWhereHas('productPrices', function ($subQ) use ($search) {
                    $subQ->where('model', 'LIKE', "%$search%");
                });
            })
            ->limit(8)
            ->get();

        return view('Frontend.Products.recommendations', compact('products'));
    }
}
