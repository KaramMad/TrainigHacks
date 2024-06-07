<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meal;


class CoachMealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coachId = 1;
        $meals = [
            [
                'type' => 'breakfast',
                'target' => 'build muscle',
                'calories' => 500,
                'protein' => 30,
                'image' => 'meal_images/breakfast.jpg',
                'description' => 'High-protein breakfast for muscle building.',
                'day_id' => 1,
                'coach_id' => $coachId,
            ],
            [
                'type' => 'lunch',
                'target' => 'lose weight',
                'calories' => 400,
                'protein' => 25,
                'image' => 'meal_images/lunch.jpg',
                'description' => 'Low-calorie lunch for weight loss.',
                'day_id' => 2,
                'coach_id' => $coachId,
            ],

        ];

        foreach ($meals as $meal) {
            Meal::updateOrCreate(['type' => $meal['type'], 'day_id' => $meal['day_id'], 'coach_id' => $meal['coach_id']], $meal);
        }
    }
}
