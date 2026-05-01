<?php

namespace App\Exports;


use App\Models\Category;
use App\Models\Wishlist;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class wishlistExport implements FromArray, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        $query = Wishlist::query();

        $wishlists = $query->orderBy('id')->get();

        $wishlists_array = [];

        foreach ($wishlists as $wishlist) {
            $product = $wishlist->product;
            $product_price = $product->getPrice();

            $categoryId = is_array($product->category_ids) ? $product->category_ids[0] ?? null : null;
            $subCategoryId = is_array($product->sub_category_ids) ? $product->sub_category_ids[0] ?? null : null;

            $categoryName = $categoryId ? Category::find($categoryId)?->name : '';
            $subCategoryName = $subCategoryId ? SubCategory::find($subCategoryId)?->name : '';

            $wishlists_array[] = [
                $wishlist->user->full_name,
                $wishlist->user->mobile,
                $wishlist->user->email,
                $categoryName,
                $subCategoryName,
                $product->name ?? '',
                $product_price ? $product_price->selling_price : 0,
            ];
        }

        return $wishlists_array;
    }


    public function headings(): array
    {
        return [
            'Customer Name',
            'Mobile',
            'Email',
            'Category',
            'Subcategory',
            'Product',
            'Price',
        ];
    }
}
