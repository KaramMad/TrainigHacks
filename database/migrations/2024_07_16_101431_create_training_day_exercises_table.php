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
        Schema::create('training_day_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('exercise_id');
            $table->unsignedBiginteger('training_day_id');

            $table->foreign('exercise_id')->references('id')
                ->on('exercises')->onDelete('cascade');
            $table->foreign('training_day_id')->references('id')
                ->on('training_days')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_day_exercises');
    }
};
