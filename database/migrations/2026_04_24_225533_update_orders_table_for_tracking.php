<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * NOTE: The actual column changes are handled by
     * 2026_04_27_000001_add_tracking_columns_to_orders_raw.php
     * using raw SQL to avoid doctrine/dbal incompatibility with PHP 8.1.
     */
    public function up()
    {
        // No-op: handled by raw SQL migration
    }

    public function down()
    {
        // No-op
    }
};
