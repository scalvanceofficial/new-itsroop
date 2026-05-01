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
        Schema::create('return_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_product_id')->constrained('return_products')->onDelete('cascade');
            $table->enum('status', ['RETURN_IN_PROGRESS', 'PRODUCT_RECEIVED', 'REFUND_INITIATE', 'REFUND_COMPLETED', 'REFUND_PROCESSED'])->default('RETURN_IN_PROGRESS');
            $table->string('product_received_remark')->nullable();
            $table->string('transaction_id')->nullable();
            $table->date('settlement_date')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_status_logs');
    }
};
