<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meal;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meals = [
            [
                'name' => 'Chicken Salad',
                'meal_type_id' => 1,
                'target' => 'lose weight',
                'diet' => 'none',
                'calories' => '250',
                'protein' => '20g',
                'image' => 'meal_images/chicken_salad.jpg',
                'warning' => null,
                'description' => 'A healthy chicken salad perfect for lunch.'
            ],
            [
                'name' => 'Vegetarian Pasta',
                'meal_type_id' => 2,
                'target' => 'build muscle',
                'diet' => 'vegetarian',
                'calories' => '300',
                'protein' => '15g',
                'image' => 'meal_images/vegetarian_pasta.jpg',
                'warning' => null,
                'description' => 'Delicious vegetarian pasta rich in protein.'
            ],
            
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }
    }
}
