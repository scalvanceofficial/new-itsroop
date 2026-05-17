<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPropertyValue;

class SyncProductImages extends Command
{
    protected $signature   = 'images:sync {--dry-run : Preview changes without writing to DB}';
    protected $description = 'Fix product primary_property_id and report any image issues.';

    public function handle(): int
    {
        $dryRun     = $this->option('dry-run');
        $products   = Product::all();
        $totalFixed = 0;

        $this->info("images:sync" . ($dryRun ? ' [DRY RUN]' : '') . "\n");
        $this->info("Scanning {$products->count()} product(s)…\n");

        // All image paths currently stored in DB (across ALL products)
        $allDbImagePaths = ProductImage::pluck('image')->toArray();

        foreach ($products as $product) {
            $this->line("── Product ID:{$product->id}  {$product->name}");

            // Fix primary_property_id if NULL
            if (empty($product->primary_property_id)) {
                $primaryPPV = ProductPropertyValue::where('product_id', $product->id)
                    ->where('is_primary', 'YES')
                    ->first();

                if ($primaryPPV) {
                    if (!$dryRun) {
                        $product->update(['primary_property_id' => $primaryPPV->property_id]);
                    }
                    $this->line("   ✔ Fixed primary_property_id → {$primaryPPV->property_id}");
                    $totalFixed++;
                } else {
                    $this->warn("   ⚠ No primary property value found.");
                }
            } else {
                $this->line("   ✔ primary_property_id = {$product->primary_property_id}");
            }

            // Report image count
            $imageCount = ProductImage::where('product_id', $product->id)->count();
            if ($imageCount > 0) {
                $this->info("   ✅ {$imageCount} image(s) linked in DB.");
            } else {
                $this->warn("   ⚠ No images in DB. Please upload via admin portal:");
                $this->warn("     → Admin → Products → click Images icon → Upload for each color variant");
            }

            $this->newLine();
        }

        // Report orphaned disk files (on disk but not linked to ANY product in DB)
        $diskDir   = storage_path('app/public/product_images');
        $allOnDisk = array_map(
            fn($f) => 'product_images/' . basename($f),
            glob("$diskDir/*") ?: []
        );
        $orphaned = array_values(array_diff($allOnDisk, $allDbImagePaths));

        if (!empty($orphaned)) {
            $this->warn("════════════════════════════════════");
            $this->warn("⚠ " . count($orphaned) . " file(s) on disk not linked to any product:");
            foreach ($orphaned as $path) {
                $this->warn("   {$path}");
            }
            $this->warn("   → Re-upload these via admin portal to link them correctly.");
        }

        $this->info("════════════════════════════════════");
        $this->info("✅ Done! Products fixed: {$totalFixed}");

        return 0;
    }
}
