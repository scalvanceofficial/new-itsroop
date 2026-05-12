<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;

$id = 2; // Testing with ID 2 which I saw in the logs
$category = Category::find($id);

if (!$category) {
    echo "Category $id not found.\n";
} else {
    echo "Attempting to delete Category: " . $category->name . "\n";
    
    $categoryIdJson = (string) $category->id;
    $exists = Product::whereJsonContains('category_ids', $categoryIdJson)->exists();
    
    if ($exists) {
        echo "CANNOT DELETE: Products are linked to this category.\n";
    } else {
        try {
            $category->delete();
            echo "SUCCESS: Category deleted.\n";
        } catch (\Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
        }
    }
}
