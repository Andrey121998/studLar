<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment primary key
            $table->string('name'); // varchar(255)
            $table->string('email')->unique(); // varchar(255) с уникальным индексом
            $table->timestamp('email_verified_at')->nullable(); // для проверки email
            $table->string('password'); // varchar(255)
            $table->rememberToken(); // remember_token varchar(100) nullable
            $table->timestamps(); // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
