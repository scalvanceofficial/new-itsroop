<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $products = Product::all();

        $export_data = [];

        foreach ($products as $product) {
            $productPrices = ProductPrice::where('product_id', $product->id)->get();

            $categoryId = is_array($product->category_ids) ? $product->category_ids[0] ?? null : null;
            $subCategoryId = is_array($product->sub_category_ids) ? $product->sub_category_ids[0] ?? null : null;

            $categoryName = Category::find($categoryId)?->name ?? '';
            $subCategoryName = SubCategory::find($subCategoryId)?->name ?? '';

            foreach ($productPrices as $price) {
                $export_data[] = [
                    $categoryName,
                    $subCategoryName,
                    $product->name,
                    $product->tag_line,
                    $product->sku,
                    $product->hsn,
                    $product->average_rating,
                    $price->label,
                    $price->actual_price,
                    $price->discount_percentage,
                    $price->discount_price,
                    $price->selling_price,
                    $price->stock,
                    $price->model ?? '',
                ];
            }
        }

        return collect($export_data);
    }

    public function headings(): array
    {
        return [
            'Category',
            'Sub Category',
            'Name',
            'tag line',
            'sku',
            'hsn',
            'average_rating',
            'Price Label',
            'Actual Price',
            'Discount Percentage',
            'Discount Price',
            'Selling Price',
            'Stock',
            'Model',
        ];
    }
}
