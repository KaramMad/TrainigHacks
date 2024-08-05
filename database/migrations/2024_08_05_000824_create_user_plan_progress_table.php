<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_plan_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('user_id')->unsigned();
            $table->unsignedBiginteger('plan_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('coach_plans')->onDelete('cascade');
            $table->enum('status', ['locked', 'unlocked'])->default('locked');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.f
     */
    public function down(): void
    {
        Schema::dropIfExists('user_plan_progress');
    }
};
