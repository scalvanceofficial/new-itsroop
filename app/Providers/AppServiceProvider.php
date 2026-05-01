<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.frontend', function ($view) {
            $categories = \App\Models\Category::where('status', 'ACTIVE')
                ->where('show_in_navbar', true)
                ->orderBy('index')
                ->take(4)
                ->get();

            foreach ($categories as $category) {
                // Fetch products for each category
                $category->menu_products = \App\Models\Product::where('status', 'ACTIVE')
                    ->whereJsonContains('category_ids', (string)$category->id)
                    ->take(5)
                    ->get();
                
                // Fetch subcategories for each category if needed
                $category->menu_subcategories = \App\Models\SubCategory::where('status', 'ACTIVE')
                    ->where('show_in_navbar', true)
                    ->where('category_id', $category->id)
                    ->take(5)
                    ->get();
            }

            $subCategories = \App\Models\SubCategory::where('status', 'ACTIVE')
                ->where('show_in_navbar', true)
                ->take(5)
                ->get();

            foreach ($subCategories as $subCategory) {
                $subCategory->menu_products = \App\Models\Product::where('status', 'ACTIVE')
                    ->whereJsonContains('sub_category_ids', (string)$subCategory->id)
                    ->take(5)
                    ->get();
            }

            $collections = \App\Models\Collection::where('status', 'ACTIVE')
                ->orderBy('index')
                ->get();

            $bestSellers = \App\Models\Product::where('status', 'ACTIVE')
                ->orderBy('views_count', 'desc')
                ->take(5)
                ->get();

            $view->with([
                'menu_categories' => $categories,
                'menu_subcategories' => $subCategories,
                'menu_collections' => $collections,
                'menu_best_sellers' => $bestSellers,
            ]);
        });
    }
}