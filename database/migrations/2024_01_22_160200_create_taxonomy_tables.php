<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->constrained()->onDelete('cascade');
            $table->json('name');
            $table->string('slug');
            $table->json('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['taxonomy_id', 'slug']);
            $table->index('sort_order');
        });
        
        Schema::create('termables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_id')->constrained()->onDelete('cascade');
            $table->morphs('termable');
            $table->timestamps();
            
            $table->unique(['term_id', 'termable_id', 'termable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('termables');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('taxonomies');
    }
};