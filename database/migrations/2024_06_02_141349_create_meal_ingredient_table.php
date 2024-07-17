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
        Schema::create('meal_ingredient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('meal_id')->unsigned()->nullable();
            $table->unsignedBiginteger('ingredient_id')->unsigned()->nullable();
            $table->foreign('meal_id')->references('id')
                ->on('meals')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')
                ->on('ingredients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_ingredient');
    }
};
