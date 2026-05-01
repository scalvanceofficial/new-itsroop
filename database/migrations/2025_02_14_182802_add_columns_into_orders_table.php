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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shiprocket_order_id')->nullable()->after('razorpay_payment_id');
            $table->string('shiprocket_shipment_id')->nullable()->after('shiprocket_order_id');
            $table->string('shiprocket_status')->nullable()->after('shiprocket_shipment_id');
            $table->decimal('shipping_charge', 10, 2)->default(0)->after('shiprocket_status');
            $table->decimal('gst', 10, 2)->default(0)->after('shipping_charge');
            $table->decimal('length', 8, 2)->default(1)->after('payment_method');
            $table->decimal('breadth', 8, 2)->default(1)->after('length');
            $table->decimal('height', 8, 2)->default(1)->after('breadth');
            $table->decimal('weight', 8, 2)->default(1)->after('height');
            $table->json('shiprocket_order_create_response')->nullable()->after('weight');
            $table->json('shiprocket_tracking_response')->nullable()->after('shiprocket_order_create_response');
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
