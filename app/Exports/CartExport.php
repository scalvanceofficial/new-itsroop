<?php

namespace App\Exports;

use App\Models\Cart;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductPrice;
use App\Models\PropertyValue;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CartExport implements FromArray, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        $query = Cart::query();

        $carts = $query->orderBy('id')->get();

        $cart_array = [];

        foreach ($carts as $cart) {
            $product = $cart->product;


            $cartData = $this->getCartData($cart);
            $totalAmount = $cartData['price'] * $cart->quantity;

            $totalAmount = $cartData['price'] * $cart->quantity;
            $categoryId = is_array($product->category_ids) ? $product->category_ids[0] ?? null : null;
            $subCategoryId = is_array($product->sub_category_ids) ? $product->sub_category_ids[0] ?? null : null;

            // Get names from DB
            $categoryName = $categoryId ? Category::find($categoryId)?->name : '';
            $subCategoryName = $subCategoryId ? SubCategory::find($subCategoryId)?->name : '';

            $cart_array[] = [
                $cart->user->full_name,
                $cart->user->mobile,
                $cart->user->email,
                $categoryName,
                $subCategoryName,
                $product->name ?? '',
                $cartData['property_values'],
                $cartData['price'],
                $cart->quantity,
                $totalAmount
            ];
        }

        return $cart_array;
    }

    public function getCartData($cart)
    {
        $product_price = ProductPrice::where('product_id', $cart->product_id)
            ->whereJsonContains('property_values', array_map('intval', $cart->property_values))
            ->first();

        $property_values = PropertyValue::whereIn('id', $cart->property_values)
            ->pluck('name')
            ->toArray();

        return [
            'price' => $product_price ? $product_price->selling_price : 0,
            'property_values' => implode(' / ', $property_values),
        ];
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
            'Properties',
            'Price',
            'Quantity',
            'Total Amount',
        ];
    }
}
