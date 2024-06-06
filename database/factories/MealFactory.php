<?php

namespace Database\Factories;
use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['breakfast', 'lunch', 'dinner', 'snack']),
            'target' => $this->faker->randomElement(['build muscle', 'lose weight']),
            'calories' => $this->faker->numberBetween(100, 1000),
            'protein' => $this->faker->numberBetween(10, 50),
            'image'=>'Meals/1717331895.jpg',
            'description' => $this->faker->paragraph,
            'day_id' => 1,
            'coach_id' => 1,
            'name' => $this->faker->paragraph,
            'meal_type_id' => $this->faker->numberBetween(1, 4),
            'diet' => $this->faker->randomElement(['vegetarian','sugar free','none']),
            'sugar' => $this->faker->numberBetween(2, 100),
            'salt' => $this->faker->numberBetween(2, 100),
            'warning' =>  $this->faker->randomElement(['High in salt, not suitable for high blood pressure', 'High in sugar, not suitable for diabetes','High in calories, not suitable for heart conditions']),
            'preparation method' => $this->faker->paragraph,

        ];



    }

}

