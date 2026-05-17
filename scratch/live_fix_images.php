<?php
/**
 * Live Server Image Fix Script
 * ─────────────────────────────
 * Scans all products, finds orphaned disk images (no DB record),
 * and links them to the product's primary property values.
 *
 * Run: php scratch/live_fix_images.php
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPropertyValue;
use App\Models\User;

// ── Admin user for created_by ─────────────────────────────────────────────────
$admin = User::first();
if (!$admin) {
    die("ERROR: No users found in database.\n");
}
echo "Using admin: ID={$admin->id}, {$admin->email}\n\n";

// ── Loop over every product ───────────────────────────────────────────────────
$products = Product::all();
echo "Found " . $products->count() . " product(s).\n\n";

foreach ($products as $product) {
    echo "═══════════════════════════════════════\n";
    echo "Product ID:{$product->id} → {$product->name}\n";

    // ── Fix primary_property_id if NULL ──────────────────────────────────────
    if (empty($product->primary_property_id)) {
        $primaryPPV = ProductPropertyValue::where('product_id', $product->id)
            ->where('is_primary', 'YES')
            ->first();

        if ($primaryPPV) {
            $product->update(['primary_property_id' => $primaryPPV->property_id]);
            echo "  ✔ Set primary_property_id = {$primaryPPV->property_id}\n";
        }
    } else {
        echo "  ✔ primary_property_id = {$product->primary_property_id}\n";
    }

    // ── Check existing DB images ──────────────────────────────────────────────
    $existingDbImages = ProductImage::where('product_id', $product->id)
        ->pluck('image')
        ->toArray();

    echo "  DB image records: " . count($existingDbImages) . "\n";

    // ── Get disk images for this product ─────────────────────────────────────
    // We look for files on disk NOT already in the DB
    $diskDir   = storage_path('app/public/product_images');
    $allOnDisk = array_map(
        fn($f) => 'product_images/' . basename($f),
        glob("$diskDir/*")
    );

    // Filter out ones already in DB
    $orphaned = array_filter(
        $allOnDisk,
        fn($f) => !in_array($f, $existingDbImages)
    );

    if (count($existingDbImages) > 0 && empty($orphaned)) {
        echo "  ✅ Already has " . count($existingDbImages) . " image(s) linked. Skipping.\n\n";
        continue;
    }

    if (empty($orphaned) && empty($existingDbImages)) {
        echo "  ⚠️  No image files on disk and no DB records. Upload via admin portal.\n\n";
        continue;
    }

    echo "  Orphaned disk files (not in DB): " . count($orphaned) . "\n";

    // ── Get property values for this product ─────────────────────────────────
    $ppvs = ProductPropertyValue::where('product_id', $product->id)->get();

    if ($ppvs->isEmpty()) {
        echo "  ⚠️  No property values found. Skipping.\n\n";
        continue;
    }

    // Get property_value_ids: primary first, then others
    $primaryPpv  = $ppvs->where('is_primary', 'YES')->values();
    $otherPpvs   = $ppvs->where('is_primary', 'NO')->values();
    $orderedPpvs = $primaryPpv->concat($otherPpvs)->values();

    // ── Distribute orphaned images across property values ─────────────────────
    $orphaned       = array_values($orphaned);
    $pvCount        = $orderedPpvs->count();
    $imagesPerPv    = max(1, (int) ceil(count($orphaned) / $pvCount));
    $chunks         = array_chunk($orphaned, $imagesPerPv);

    foreach ($chunks as $chunkIndex => $chunk) {
        $ppv = $orderedPpvs[$chunkIndex] ?? $orderedPpvs->last();
        foreach ($chunk as $imagePath) {
            DB::table('product_images')->insert([
                'product_id'        => $product->id,
                'property_value_id' => $ppv->property_value_id,
                'image'             => $imagePath,
                'created_by'        => $admin->id,
                'updated_by'        => $admin->id,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            echo "  ✔ INSERTED: {$imagePath} → property_value_id={$ppv->property_value_id}\n";
        }
    }

    // ── Verify ────────────────────────────────────────────────────────────────
    $finalCount = ProductImage::where('product_id', $product->id)->count();
    echo "  ✅ Total DB images for this product: {$finalCount}\n\n";
}

echo "═══════════════════════════════════════\n";
echo "✅ Done! Run these next:\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan view:clear\n";
