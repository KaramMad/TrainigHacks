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
            $table->foreignId('exercise_type_id')->nullable()->constrained()->onDelete('set null')->default('null');
            $table->string('exercise_name');
            $table->string('description');
            $table->unsignedBigInteger('calories');
            $table->string('time')->nullable();
            $table->unsignedBigInteger('reps')->nullable();
            $table->string('image')->nullable();
            $table->string('video_link');
            $table->enum('target',['lose_weight','build_muscle','keep_fit']);
            $table->enum('diseases',['heart','none','knee','breath']);
            $table->enum('level',['beginner','intermediate','advanced']);
            $table->enum('gender',['male','female']);
            $table->enum('choose',['equipment','no_equipment'])->nullable();
            $table->boolean('private')->default(0);

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
