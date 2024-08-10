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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('calories')->default(0);
            $table->integer('total_calories')->default(0);
            $table->integer('Number_of_exercises')->default(0);
            $table->time('time')->default('00:00:00');
            $table->time('total_time')->default('00:00:00');
            $table->date('report_date');
            $table->integer('steps')->default(0);
            $table->decimal('weight', 2)->nullable();
            $table->decimal('bmi', 2)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'report_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
