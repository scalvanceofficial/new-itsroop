<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;

echo "=== PRODUCTS ===\n";
foreach (Product::all() as $product) {
    echo "ID: {$product->id}, Name: {$product->name}, Primary Property ID: {$product->primary_property_id}\n";
    echo "Primary property values: " . json_encode($product->primary_property_values) . "\n";
}

echo "\n=== PRODUCT IMAGES ===\n";
foreach (ProductImage::all() as $image) {
    echo "ID: {$image->id}, Product ID: {$image->product_id}, Property Value ID: {$image->property_value_id}, Image Path: {$image->image}\n";
}
