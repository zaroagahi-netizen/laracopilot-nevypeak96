<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->enum('type', ['physical', 'digital'])->default('physical');
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->string('digital_file_path')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_customizable')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['active', 'type']);
            $table->index(['min_age', 'max_age']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};