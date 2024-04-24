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
        Schema::create('users', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('tall')->nullable();
            $table->integer('weight')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->enum('target',['lose weight','build muscle','keep fit'])->nullable();
            $table->enum('diseases',['heart','knee','breath'])->nullable();
            $table->time('preferred_time')->nullable();
            $table->string('picture')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

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
