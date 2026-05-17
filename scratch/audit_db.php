<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- PRODUCTS ---\n";
foreach (App\Models\Product::all() as $product) {
    echo "ID: {$product->id}, Name: {$product->name}, Primary Property ID: {$product->primary_property_id}\n";
    echo "Images count: " . $product->productImages()->count() . "\n";
    foreach ($product->productImages as $img) {
        echo "  Image ID: {$img->id}, Property Value ID: {$img->property_value_id}, Path: {$img->image}\n";
    }
}
