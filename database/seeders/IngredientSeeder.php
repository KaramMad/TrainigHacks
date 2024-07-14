<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Meal;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\Ingredient::factory()->count(6)->create();
        // foreach (Ingredient::all() as $ingredients) {
        //     $meal = Meal::inRandomOrder()->take(rand(1, 6))->pluck('id');
        //     $ingredients->meals()->attach($meal);
        // }
        \App\Models\Ingredient::factory()->count(6)->create();
        if (Meal::count() == 0) {
            \App\Models\Meal::factory()->count(10)->create();
        }

        foreach (Ingredient::all() as $ingredient) {
            $meals = Meal::inRandomOrder()->take(rand(1, 6))->pluck('id');
            $ingredient->meals()->attach($meals);
        }
    }
}
