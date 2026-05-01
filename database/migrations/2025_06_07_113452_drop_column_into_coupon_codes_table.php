<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_codes', function (Blueprint $table) {
            $table->dropColumn('is_applicable_to_all');
            $table->dropColumn('product_ids');
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('max_amount');
            $table->dropColumn('use_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
