<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPropertyValue;
use App\Models\Property;
use App\Models\PropertyValue;

echo "=== PRODUCTS ===\n";
foreach (Product::all() as $product) {
    echo "ID: {$product->id}, Name: {$product->name}, Primary Property ID: {$product->primary_property_id}\n";
    echo "  Primary property_values: " . json_encode($product->primary_property_values) . "\n";
}

echo "\n=== PRODUCT PROPERTY VALUES ===\n";
foreach (ProductPropertyValue::all() as $ppv) {
    $prop = Property::find($ppv->property_id);
    $val  = \App\Models\PropertyValue::find($ppv->property_value_id);
    echo "ID: {$ppv->id}, Product: {$ppv->product_id}, Property: " . ($prop ? $prop->name : 'N/A')
        . " (ID:{$ppv->property_id}), Value: " . ($val ? $val->name : 'N/A')
        . " (ID:{$ppv->property_value_id}), is_primary: {$ppv->is_primary}\n";
}

echo "\n=== PRODUCT IMAGES (DB) ===\n";
$imgs = ProductImage::all();
if ($imgs->isEmpty()) {
    echo "NO records found in product_images table!\n";
} else {
    foreach ($imgs as $img) {
        echo "ID: {$img->id}, Product: {$img->product_id}, PropValueID: {$img->property_value_id}, Path: {$img->image}\n";
    }
}

echo "\n=== FILES ON DISK (storage/app/public/product_images/) ===\n";
$dir = storage_path('app/public/product_images');
foreach (glob("$dir/*") as $file) {
    $filename = basename($file);
    echo "product_images/{$filename}\n";
}
