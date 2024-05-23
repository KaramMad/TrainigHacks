<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meal_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        DB::table('meal_types')->insert([
            ['name' => 'breakfast'],
            ['name' => 'lunch'],
            ['name' => 'dinner'],
            ['name' => 'snack'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_types');
    }
};
