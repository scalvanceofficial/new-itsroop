<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_products', function (Blueprint $table) {
            $table->enum('status', ['RETURN_IN_PROGRESS', 'PRODUCT_RECEIVED', 'REFUND_INITIATE', 'REFUND_COMPLETED'])->default('RETURN_IN_PROGRESS')->after('remark');
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
