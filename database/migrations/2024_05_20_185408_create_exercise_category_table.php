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
        Schema::create('exercise_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('exercise_id');
            $table->unsignedBiginteger('category_id');
            $table->foreign('exercise_id')->references('id')
                ->on('exercises')->onDelete('cascade');
            $table->foreign('category_id')->references('id')
                ->on('categories')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_category');
    }
};
