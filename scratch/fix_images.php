<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;

// Find first admin user ID to use for created_by
$adminUser = User::first();
echo "Using admin user: ID={$adminUser->id}, Email={$adminUser->email}\n\n";

// Fix the product's primary_property_id
$product = Product::find(1);
$primaryPPV = $product->productPropertyValues()->where('is_primary', 'YES')->first();
$primaryPropertyId = $primaryPPV ? $primaryPPV->property_id : 1;
$product->update(['primary_property_id' => $primaryPropertyId]);
echo "Set primary_property_id = {$primaryPropertyId} on product '{$product->name}'\n\n";

// The 4 disk images → assign to property values
// property_value_id=2 (Black, is_primary=YES), property_value_id=1 (White)
$diskImages = [
    'product_images/9UonEmj4OiXa9SAzXC2HC6AI7sQgiMfkzRMVTvxG.webp' => 2,
    'product_images/nm6gZ2WeQ0TrQEFNpelBeUAV3yO5gGe8XEp62hb4.jpg'  => 2,
    'product_images/ovmwaIHKqGDpZnnulMam6yPYHNgP5EqmYCU5EwFR.webp' => 1,
    'product_images/txUKg7EbSDxkfoADIMTDby9fofUeEDI5VyDsw9Ju.webp' => 1,
];

echo "Inserting product_images records:\n";
foreach ($diskImages as $imagePath => $propertyValueId) {
    $exists = ProductImage::where('product_id', $product->id)
        ->where('image', $imagePath)->exists();

    if ($exists) {
        echo "  SKIP (already exists): {$imagePath}\n";
        continue;
    }

    // Use DB insert directly to bypass BaseModel boot (no auth in CLI)
    \Illuminate\Support\Facades\DB::table('product_images')->insert([
        'product_id'        => $product->id,
        'property_value_id' => $propertyValueId,
        'image'             => $imagePath,
        'created_by'        => $adminUser->id,
        'updated_by'        => $adminUser->id,
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);
    echo "  INSERTED: {$imagePath} → property_value_id={$propertyValueId}\n";
}

echo "\n✅ Final product_images records in DB:\n";
foreach (ProductImage::where('product_id', $product->id)->get() as $img) {
    echo "  ID:{$img->id}  prop_val_id:{$img->property_value_id}  path:{$img->image}\n";
    echo "  URL: " . asset(\Illuminate\Support\Facades\Storage::url($img->image)) . "\n\n";
}
