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
        Schema::create('coach_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('coach_id')->unsigned()->nullable();
            $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
            $table->string('plan_name');
            $table->string('description');
            $table->enum('target',['lose_weight', 'build_muscle', 'keep_fit']);
            $table->enum('choose', ['equipment', 'no_equipment']);
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_plans');
    }
};
