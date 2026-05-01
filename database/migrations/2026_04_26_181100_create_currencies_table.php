<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. USD, GBP, INR
            $table->string('name'); // e.g. US Dollar
            $table->string('symbol'); // e.g. $
            $table->decimal('exchange_rate', 16, 8)->default(1.0); // Rate against GBP (base)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
