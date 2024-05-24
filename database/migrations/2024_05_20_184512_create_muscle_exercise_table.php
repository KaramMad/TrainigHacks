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
        Schema::create('muscle_exercise', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('exercise_id');
            $table->unsignedBiginteger('muscle_id');
            $table->string('exercise_count')->nullable();
            $table->string('total_time')->nullable();
            $table->string('total_calories')->nullable();
            $table->foreign('exercise_id')->references('id')
                ->on('exercises')->onDelete('cascade');
            $table->foreign('muscle_id')->references('id')
                ->on('muscles')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muscle_exercise');
    }
};
