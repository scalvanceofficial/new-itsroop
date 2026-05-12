<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Slider;

$product = Product::first();
if ($product) {
    echo "Product ID: " . $product->id . "\n";
    echo "Product Route Key: " . $product->route_key . "\n";
    $decoded = \Hashids::connection('App\Models\Product')->decode($product->route_key);
    echo "Decoded Product ID: " . ($decoded[0] ?? 'FAILED') . "\n";
}

$slider = Slider::first();
if ($slider) {
    echo "Slider ID: " . $slider->id . "\n";
    echo "Slider Route Key: " . $slider->route_key . "\n";
    $decoded = \Hashids::connection('App\Models\Slider')->decode($slider->route_key);
    echo "Decoded Slider ID: " . ($decoded[0] ?? 'FAILED') . "\n";
}
