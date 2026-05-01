<?php

use Razorpay\Api\Customer;
use App\Exports\CartsExport;
use App\Exports\OrdersExport;
use App\Exports\ProductsExport;
use App\Exports\CustomersExport;
use App\Exports\WishlistsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\WishlistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\CouponCodeController;
use App\Http\Controllers\Admin\CredentialController;
use App\Http\Controllers\Admin\ShiprocketController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ReturnProductController;
use App\Http\Controllers\Admin\ReturnStatusLogController;
use App\Http\Controllers\Admin\VisitorLogController;

//End of use statements


Route::prefix('export')->group(function () {
    Route::get('products', fn() => Excel::download(new ProductsExport, 'products.xlsx'))->name('export.products');
});


Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard.index');
    });
});

Route::middleware(['auth', 'admin', 'preventBackHistory'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::post('dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
        Route::post('dashboard/wishlist-products', [DashboardController::class, 'getWishlistProduct'])->name('dashboard.wishlist-products');
        Route::post('dashboard/selling-products', [DashboardController::class, 'getSellingProduct'])->name('dashboard.selling-products');
        Route::post('dashboard/order-summary', [DashboardController::class, 'getOrderSummary'])->name('dashboard.order-summary');

        //Roles
        Route::resource('roles', RoleController::class);
        Route::post('roles/data', [RoleController::class, 'data'])->name('roles.data');
        Route::post('roles/list', [RoleController::class, 'list'])->name('roles.list');

        //Permissions
        Route::get('roles/{role}/permission/show', [RoleController::class, 'permissionsShow'])->name('roles.permissions.show');
        Route::post('roles/{role}/permission/update', [RoleController::class, 'permissionsUpdate'])->name('roles.permissions.update');

        //Roles
        Route::resource('roles', RoleController::class);
        Route::post('roles/data', [RoleController::class, 'data'])->name('roles.data');
        Route::post('roles/list', [RoleController::class, 'list'])->name('roles.list');

        //Employees
        Route::resource('employees', EmployeeController::class);
        Route::post('employees/data', [EmployeeController::class, 'data'])->name('employees.data');
        Route::post('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
        Route::post('employees/change-status', [EmployeeController::class, 'changeStatus'])->name('employees.change.status');

        //Customers
        Route::resource('customers', CustomerController::class);
        Route::post('customers/data', [CustomerController::class, 'data'])->name('customers.data');
        Route::post('customers/change-status', [CustomerController::class, 'changeStatus'])->name('customers.change.status');
        Route::get('customers/{user}/profile', [CustomerController::class, 'profile'])->name('customers.profile');
        Route::post('customers/export/excel', [CustomerController::class, 'export'])->name('customers.export.excel');

        // Properties
        Route::resource('properties', PropertyController::class);
        Route::post('properties/data', [PropertyController::class, 'data'])->name('properties.data');
        Route::post('properties/change-status', [PropertyController::class, 'changeStatus'])->name('properties.change.status');
        Route::post('properties/values/create', [PropertyController::class, 'propertyValueCreate'])->name('properties.values.create');
        Route::post('properties/index/update', [PropertyController::class, 'updateIndex'])->name('properties.index.update');

        // Categories
        Route::resource('categories', CategoryController::class);
        Route::post('categories/data', [CategoryController::class, 'data'])->name('categories.data');
        Route::post('categories/change-status', [CategoryController::class, 'changeStatus'])->name('categories.change.status');
        Route::post('categories/index/update', [CategoryController::class, 'updateIndex'])->name('categories.index.update');
        //sub-categories
        Route::prefix('categories/{category}')->name('categories.')->group(
            function () {
                Route::resource('sub_categories', SubCategoryController::class);
                Route::post('sub_categories/data', [SubCategoryController::class, 'data'])->name('sub_categories.data');
                Route::post('sub_categories/change-status', [SubCategoryController::class, 'changeStatus'])->name('sub_categories.change.status');
            }
        );

        // Sliders
        Route::resource('sliders', SliderController::class);
        Route::post('sliders/data', [SliderController::class, 'data'])->name('sliders.data');
        Route::post('sliders/change-status', [SliderController::class, 'changeStatus'])->name('sliders.change.status');
        Route::post('sliders/index/update', [SliderController::class, 'updateIndex'])->name('sliders.index.update');

        // Videos
        Route::resource('videos', VideoController::class);
        Route::post('videos/data', [VideoController::class, 'data'])->name('videos.data');
        Route::post('videos/change-status', [VideoController::class, 'changeStatus'])->name('videos.change.status');
        Route::post('videos/index/update', [VideoController::class, 'updateIndex'])->name('videos.index.update');

        // Enquiries
        Route::resource('enquiries', EnquiryController::class);
        Route::post('enquiries/data', [EnquiryController::class, 'data'])->name('enquiries.data');

        // Product
        Route::resource('products', ProductController::class);
        Route::post('products/data', [ProductController::class, 'data'])->name('products.data');
        Route::post('products/change-home_featured-status', [ProductController::class, 'changeHomeFeaturedStatus'])->name('products.change.home_featured.status');
        Route::post('products/change-status', [ProductController::class, 'changeStatus'])->name('products.change.status');
        Route::post('products/index/update', [ProductController::class, 'updateIndex'])->name('products.index.update');
        Route::get('products/{product}/images/create', [ProductController::class, 'createImages'])->name('products.images.create');
        Route::post('products/{product}/images/form', [ProductController::class, 'imageForm'])->name('products.images.form');
        Route::post('products/{product}/images/store', [ProductController::class, 'storeImages'])->name('products.images.store');
        Route::post('products/{product}/images/destroy', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::get('products/{product}/prices/create', [ProductController::class, 'createPrices'])->name('products.prices.create');
        Route::post('products/{product}/prices/store', [ProductController::class, 'storePrices'])->name('products.prices.store');
        Route::post('products/property-values', [ProductController::class, 'getPropertyValues'])->name('products.property-values');
        Route::post('products/sub-categories', [ProductController::class, 'getSubCategories'])->name('products.sub-categories');

        // Collections
        Route::resource('collections', CollectionController::class);
        Route::post('collections/data', [CollectionController::class, 'data'])->name('collections.data');
        Route::post('collections/change-status', [CollectionController::class, 'changeStatus'])->name('collections.change.status');
        Route::post('collections/index/update', [CollectionController::class, 'updateIndex'])->name('collections.index.update');

        // Carts
        Route::get('carts', [CartController::class, 'index'])->name('carts.index');
        Route::post('carts/data', [CartController::class, 'data'])->name('carts.data');
        Route::post('carts/export/excel', [CartController::class, 'export'])->name('carts.export.excel');

        // Wishlists
        Route::get('wishlists', [WishlistController::class, 'index'])->name('wishlists.index');
        Route::post('wishlists/data', [WishlistController::class, 'data'])->name('wishlists.data');
        Route::post('wishlists/export/excel', [WishlistController::class, 'export'])->name('wishlists.export.excel');

        //Orders
        Route::resource('orders', OrderController::class);
        Route::post('orders/data', [OrderController::class, 'data'])->name('orders.data');
        Route::post('orders/update-tracking', [OrderController::class, 'updateTracking'])->name('orders.update.tracking');
        Route::post('order/cancel', [OrderController::class, 'adminCancelOrder'])->name('order.cancel');
        Route::post('restore/order', [OrderController::class, 'restoreOrder'])->name('restore.order');
        Route::get('orders/{order}/pdf', [OrderController::class, 'pdf'])->name('orders.pdf.download');
        Route::post('orders/export/excel', [OrderController::class, 'export'])->name('orders.export.excel');

        //Return Products
        Route::resource('return-products', ReturnProductController::class);
        Route::post('return-products/data', [ReturnProductController::class, 'data'])->name('return-product.data');
        Route::post('return-products/order-products', [ReturnProductController::class, 'orderProduct'])->name('return-product.order-product');
        Route::post('return-products/status/update', [ReturnProductController::class, 'updateStatus'])->name('return-product.status.update');
        Route::post('return-products/export/excel', [ReturnProductController::class, 'export'])->name('return-product.export.excel');

        // Shiprocket
        Route::prefix('shiprocket')->name('shiprocket.')->group(function () {
            Route::post('order/create', [ShiprocketController::class, 'createOrder'])->name('order.create');
            Route::post('order/track', [ShiprocketController::class, 'trackOrder'])->name('order.track');
            Route::post('order/cancel', [ShiprocketController::class, 'cancelOrder'])->name('order.cancel');
            Route::post('order/update', [ShiprocketController::class, 'updateOrder'])->name('order.update');
        });
        // subscribers
        Route::resource('subscribers', SubscriberController::class);
        Route::post('subscribers/data', [SubscriberController::class, 'data'])->name('subscribers.data');

        //Reviews
        Route::resource('reviews', ReviewController::class);
        Route::post('reviews/data', [ReviewController::class, 'data'])->name('reviews.data');
        Route::post('reviews/change-status', [ReviewController::class, 'changeStatus'])->name('reviews.change.status');

        //Testimonial
        Route::resource('testimonials', TestimonialController::class);
        Route::post('testimonials/data', [TestimonialController::class, 'data'])->name('testimonials.data');
        Route::post('testimonials/change-status', [TestimonialController::class, 'changeStatus'])->name('testimonials.change.status');
        Route::post('testimonials/index/update', [TestimonialController::class, 'updateIndex'])->name('testimonials.index.update');

        //blogs
        Route::resource('blogs', BlogController::class);
        Route::post('blogs/data', [BlogController::class, 'data'])->name('blogs.data');
        Route::post('blogs/change-status', [BlogController::class, 'changeStatus'])->name('blogs.change.status');
        Route::post('blogs/index/update', [BlogController::class, 'updateIndex'])->name('blogs.index.update');

        //news
        Route::resource('news', NewsController::class);
        Route::post('news/data', [NewsController::class, 'data'])->name('news.data');
        Route::post('news/change-status', [NewsController::class, 'changeStatus'])->name('news.change.status');
        Route::post('news/index/update', [NewsController::class, 'updateIndex'])->name('news.index.update');

        // credentials
        Route::resource('credentials', CredentialController::class);
        Route::post('credentials/data', [CredentialController::class, 'data'])->name('credentials.data');

        //coupon code
        Route::resource('coupon-codes', CouponCodeController::class);
        Route::post('coupon-codes/data', [CouponCodeController::class, 'data'])->name('coupon-codes.data');
        Route::post('coupon-codes/change-status', [CouponCodeController::class, 'changeStatus'])->name('coupon-codes.change.status');

        // visitorlogs
        Route::resource('visitorlog', VisitorLogController::class);
        Route::post('visitorlog/data', [VisitorLogController::class, 'data'])->name('visitorlog.data');

        // currencies
        Route::resource('currencies', \App\Http\Controllers\Admin\CurrencyController::class);
        Route::post('currencies/data', [\App\Http\Controllers\Admin\CurrencyController::class, 'data'])->name('currencies.data');
        Route::post('currencies/change-status', [\App\Http\Controllers\Admin\CurrencyController::class, 'changeStatus'])->name('currencies.change.status');
    });
});
