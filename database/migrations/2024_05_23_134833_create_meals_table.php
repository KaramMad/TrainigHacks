<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name")->default('null');
            $table->enum('type', ['breakfast', 'lunch', 'dinner', 'snack',])->index();
            $table->enum('target', ['build muscle', 'lose weight'])->nullable();
            $table->enum('diet', ['vegetarian', 'sugar free', 'none'])->nullable()->default('none');
            $table->string('calories');
            $table->string('protein');
            $table->string('image')->nullable();
            $table->text('description');
            $table->text('warning')->nullable();
            $table->integer('day_id')->unsigned()->nullable();
            $table->integer('coach_id')->unsigned()->nullable();
            $table->integer('meal_type_id')->unsigned()->nullable();
            $table->foreign('meal_type_id')->references('id')->on('meal_types')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('training_days')->onDelete('cascade');
            $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
