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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('category_ids');
            $table->string('name');
            $table->string('tag_line');
            $table->string('slug');
            $table->string('sku')->unique();
            $table->string('hsn');
            $table->string('model');
            $table->text('highlights');
            $table->text('description');
            $table->foreignId('views_count')->default(0);
            $table->foreignId('reviews_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('index')->default(0);
            $table->enum('featured', ['ACTIVE', 'INACTIVE'])->default('INACTIVE');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
