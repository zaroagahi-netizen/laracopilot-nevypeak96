<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * ZARO Platform - Kullanıcı tablosu
     * Relation type: Anne, Baba veya Öğretmen
     * Children data: Maksimum 6 çocuk bilgisi JSONB array formatında
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // ZARO specific fields
            $table->enum('relation_type', ['Anne', 'Baba', 'Öğretmen'])->default('Anne');
            $table->jsonb('children_data')->nullable()->comment('Maksimum 6 çocuk: [{name, birth_date, gender}, ...]');
            $table->boolean('kvkk_accepted')->default(false)->comment('KVKK onay durumu');
            $table->date('birth_date')->nullable()->comment('Kullanıcı doğum tarihi (18+ kontrolü için)');
            $table->string('phone')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};