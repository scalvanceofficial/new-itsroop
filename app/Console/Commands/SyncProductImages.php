<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPropertyValue;
use App\Models\User;

class SyncProductImages extends Command
{
    protected $signature   = 'images:sync {--dry-run : Preview changes without writing to DB}';
    protected $description = 'Sync orphaned product image files on disk into the product_images database table.';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $admin  = User::first();

        if (!$admin) {
            $this->error('No users found in database. Aborting.');
            return 1;
        }

        $this->info("Running images:sync" . ($dryRun ? ' [DRY RUN]' : '') . "\n");

        $products = Product::all();
        $this->info("Scanning {$products->count()} product(s)…\n");

        $totalInserted = 0;
        $totalFixed    = 0;

        foreach ($products as $product) {
            $this->line("── Product ID:{$product->id}  {$product->name}");

            // ── Fix primary_property_id if NULL ──────────────────────────────
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
                }
            }

            // ── Find orphaned disk files ──────────────────────────────────────
            $existingDbImages = ProductImage::where('product_id', $product->id)
                ->pluck('image')
                ->toArray();

            $diskDir   = storage_path('app/public/product_images');
            $allOnDisk = array_map(
                fn($f) => 'product_images/' . basename($f),
                glob("$diskDir/*") ?: []
            );

            $orphaned = array_values(array_filter(
                $allOnDisk,
                fn($f) => !in_array($f, $existingDbImages)
            ));

            if (empty($orphaned)) {
                $count = count($existingDbImages);
                $this->line("   ✅ {$count} image(s) already linked. Nothing to do.");
                $this->newLine();
                continue;
            }

            $this->line("   Found " . count($orphaned) . " orphaned file(s) on disk.");

            // ── Get property values ordered: primary first ────────────────────
            $ppvs = ProductPropertyValue::where('product_id', $product->id)->get();

            if ($ppvs->isEmpty()) {
                $this->warn("   ⚠ No property values found — skipping.");
                $this->newLine();
                continue;
            }

            $ordered = $ppvs->sortByDesc(fn($p) => $p->is_primary === 'YES')->values();
            $pvCount = $ordered->count();
            $chunks  = array_chunk($orphaned, (int) ceil(count($orphaned) / $pvCount));

            foreach ($chunks as $i => $chunk) {
                $ppv = $ordered[$i] ?? $ordered->last();
                foreach ($chunk as $imagePath) {
                    $this->line("   " . ($dryRun ? '[DRY]' : '') . " INSERT: {$imagePath} → prop_value_id={$ppv->property_value_id}");

                    if (!$dryRun) {
                        DB::table('product_images')->insert([
                            'product_id'        => $product->id,
                            'property_value_id' => $ppv->property_value_id,
                            'image'             => $imagePath,
                            'created_by'        => $admin->id,
                            'updated_by'        => $admin->id,
                            'created_at'        => now(),
                            'updated_at'        => now(),
                        ]);
                    }
                    $totalInserted++;
                }
            }

            $this->newLine();
        }

        $this->info("════════════════════════════════════");
        $this->info("✅ Done!");
        $this->info("   Products fixed  : {$totalFixed}");
        $this->info("   Images inserted : {$totalInserted}" . ($dryRun ? ' (dry run — nothing written)' : ''));

        return 0;
    }
}
