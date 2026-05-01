<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\EnquiryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\WishlistController;

Route::middleware(['auth'])->group(function () {
    Route::name('frontend.')->group(function () {
        // Orders
        Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'orderDetails'])->name('orders.details');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
        Route::post('/orders/{order}/return', [OrderController::class, 'returnOrder'])->name('orders.return');
        Route::get('orders/{order}/pdf', [OrderController::class, 'pdf'])->name('orders.pdf.download');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/stripe/initiate', [OrderController::class, 'initiatePayment'])->name('orders.stripe-initiate');
        Route::get('/stripe/success', [OrderController::class, 'stripeSuccess'])->name('orders.stripe-success');
        Route::get('/stripe/cancel', [OrderController::class, 'stripeCancel'])->name('orders.stripe-cancel');

        //Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [CartController::class, 'addCartProduct'])->name('cart.add');
        Route::post('/cart/remove', [CartController::class, 'removeCartProduct'])->name('cart.remove');
        Route::post('/cart/update', [CartController::class, 'updateCartProduct'])->name('cart.update');
        Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

        // Addresses
        Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses');
        Route::post('/addresses/store', [ProfileController::class, 'storeAddress'])->name('addresses.store');
        Route::post('/addresses/update', [ProfileController::class, 'updateAddress'])->name('addresses.update');
        Route::post('/addresses/delete', [ProfileController::class, 'deleteAddress'])->name('addresses.delete');

        // Reviews
        Route::post('/reviews/store', [ProductController::class, 'storeReview'])->name('reviews.store');

        // Wishlists
        Route::get('/wishlists', [WishlistController::class, 'myWishlists'])->name('wishlists');
        Route::post('/wishlists/toggle', [WishlistController::class, 'toggleWishlist'])->name('wishlists.toggle');
        Route::get('/wishlists/count', [WishlistController::class, 'getWishlistCount'])->name('wishlists.count');
    });
});

Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('/return-and-exchange', [HomeController::class, 'returnExchange'])->name('return-and-exchange');
    Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    Route::get('/shipping', [HomeController::class, 'shipping'])->name('shipping');

    Route::get('/products/men',     [ProductController::class, 'index'])->name('products.men');
    Route::get('/products/women',   [ProductController::class, 'index'])->name('products.women');
    Route::get('/products/unisex',  [ProductController::class, 'index'])->name('products.unisex');
    Route::get('/products/{category_slug?}', [ProductController::class, 'index'])->name('products');
    Route::get('/products/{slug}/details', [ProductController::class, 'productDetails'])->name('products.product-details');
    Route::post('/products/{product}/images', [ProductController::class, 'getProductImages'])->name('products.images');
    Route::post('/products/{product}/price', [ProductController::class, 'getProductPrice'])->name('products.price');
    Route::post('/products/{product}/price', [ProductController::class, 'getProductPrice'])->name('products.price');
    Route::post('/products/filters', [ProductController::class, 'getFilteredProducts'])->name('products.filters');
    Route::get('/search-recommendations', [ProductController::class, 'searchRecommendations'])->name('products.search-recommendations');
    Route::post('/product/apply-coupon', [ProductController::class, 'applyCouponCode'])->name('apply.coupon.code');

    // Auth
    Route::post('/signin', [AuthController::class, 'signIn'])->name('signin');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('/signup', [AuthController::class, 'signUp'])->name('signup');

    //blog -details
    Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
    Route::get('/blogs/{slug}/details', [HomeController::class, 'blogDetails'])->name('blogs.details');

    //news
    Route::get('/news', [HomeController::class, 'news'])->name('news');
    Route::get('/news/{slug}/details', [HomeController::class, 'newsDetails'])->name('news.details');

    //articles
    Route::get('/articles', [HomeController::class, 'article'])->name('articles');
    Route::get('/greenhouse-articles', [HomeController::class, 'greenhouseArticle'])->name('greenhouse-articles');

    //enquiry
    Route::post('/enquiry/store', [EnquiryController::class, 'storeEnquiry'])->name('enquiry.store');

    Route::post('/subscribe-enquiry', [HomeController::class, 'subscribeEnquiry'])->name('subscribe-enquiry');
    Route::post('/change-currency', [HomeController::class, 'changeCurrency'])->name('change-currency');

    // Currency switcher
    Route::post('/set-currency', function (\Illuminate\Http\Request $request) {
        $currency = $request->input('currency', 'GBP');
        $exists = \App\Models\Currency::where('code', $currency)->where('is_active', true)->exists();
        if ($exists) {
            session(['currency' => $currency]);
        }
        return redirect()->back();
    })->name('set-currency');
});
