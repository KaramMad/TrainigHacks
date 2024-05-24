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
        Schema::create('exercise_focus_area', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('exercise_id');
            $table->unsignedBiginteger('focus_area_id');


            $table->foreign('exercise_id')->references('id')
                ->on('exercises')->onDelete('cascade');
            $table->foreign('focus_area_id')->references('id')
                ->on('focus_areas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_focus_area');
    }
};
