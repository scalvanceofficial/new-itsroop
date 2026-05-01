<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify the ENUM column to add new values
        DB::statement("ALTER TABLE return_products MODIFY status ENUM(
            'RETURN_IN_PROGRESS',
            'PRODUCT_RECEIVED',
            'REFUND_INITIATE',
            'REFUND_COMPLETED',
            'PRODUCT_RECEIVED_REMARK',
            'SETTLEMENT_DATE'
        ) DEFAULT 'RETURN_IN_PROGRESS'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to original ENUM (without the new values)
        DB::statement("ALTER TABLE return_products MODIFY status ENUM(
            'RETURN_IN_PROGRESS',
            'PRODUCT_RECEIVED',
            'REFUND_INITIATE',
            'REFUND_COMPLETED'
        ) DEFAULT 'RETURN_IN_PROGRESS'");
    }
};
