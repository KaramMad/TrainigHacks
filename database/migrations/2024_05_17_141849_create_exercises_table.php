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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('exercise_name');
            $table->string('description');
            $table->string('calories');
            $table->string('time')->nullable();
            $table->string('reps')->nullable();
            $table->string('sets')->nullable();
            $table->string('image');
            $table->string('video_link');
            $table->enum('target',['lose_weight','build_muscle','keep_fit']);
            $table->enum('level',['beginner','intermediate','advanced']);
            $table->enum('gender',['male','female']);
            $table->enum('choose',['equipment','no_eqiupment'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
