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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('challenge_name');
            $table->string('timer')->nullable();
            $table->unsignedBigInteger('counter')->nullable();
            $table->enum('type',['timer','counter']);
            $table->string('image')->nullable();
            $table->string('secondry_image')->nullable();
            $table->string('gif')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
