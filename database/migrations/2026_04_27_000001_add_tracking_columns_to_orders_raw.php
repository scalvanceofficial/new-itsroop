<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Get existing columns directly to avoid doctrine/dbal issues
        $columns = collect(DB::select('SHOW COLUMNS FROM orders'))->pluck('Field')->toArray();

        // Add tracking_number if missing (might exist as awb_code - rename it)
        if (in_array('awb_code', $columns) && !in_array('tracking_number', $columns)) {
            DB::statement('ALTER TABLE orders CHANGE awb_code tracking_number VARCHAR(255) NULL');
        } elseif (!in_array('tracking_number', $columns)) {
            DB::statement('ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(255) NULL');
        }

        // Add courier_name if missing
        if (!in_array('courier_name', $columns)) {
            DB::statement('ALTER TABLE orders ADD COLUMN courier_name VARCHAR(255) NULL');
        }

        // Add tracking_url if missing
        if (!in_array('tracking_url', $columns)) {
            DB::statement('ALTER TABLE orders ADD COLUMN tracking_url VARCHAR(255) NULL');
        }

        // Add estimated_delivery_date if missing
        if (!in_array('estimated_delivery_date', $columns)) {
            DB::statement('ALTER TABLE orders ADD COLUMN estimated_delivery_date DATE NULL');
        }

        // Add shiprocket_tracking_response if missing
        if (!in_array('shiprocket_tracking_response', $columns)) {
            DB::statement('ALTER TABLE orders ADD COLUMN shiprocket_tracking_response JSON NULL');
        }
    }

    public function down()
    {
        // No destructive rollback
    }
};
