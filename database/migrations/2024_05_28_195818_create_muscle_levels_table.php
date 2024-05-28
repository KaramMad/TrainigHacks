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
        Schema::create('muscle_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('men_image')->nullable();
            $table->string('women_image')->nullable();
            $table->foreignId('muscle_id')->constrained('muscles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muscle_levels');
    }
};
