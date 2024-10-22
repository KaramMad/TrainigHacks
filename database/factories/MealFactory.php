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
    protected $model = \App\Models\Meal::class;

    public function definition(): array
    {
        return [
            'target' => $this->faker->randomElement([/*'build muscle',*/'lose weight', /*'keep fit'*/]),
            'calories' => $this->faker->numberBetween(100, 1000),
            'protein' => $this->faker->numberBetween(10, 50),
            'image' => 'Meals/1721938375.png',
            'description' => $this->faker->paragraph,
            'day_id' => $this->faker->randomElement([/*'1', '2', '3', '4', '5'*/'1', null]),
            'coach_id' => $this->faker->randomElement(['1', null]),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['vegetarian', 'sugar free', 'none']),
            'categoryName' => $this->faker->randomElement(['breakfast', 'lunch', 'dinner', 'snack', null]),
            'sugar' => $this->faker->numberBetween(2, 100),
            'salt' => $this->faker->numberBetween(2, 100),
            'warning' =>  $this->faker->randomElement(['High in salt, not suitable for high blood pressure', 'High in sugar, not suitable for diabetes', 'High in calories, not suitable for heart conditions', null]),
            'preparation_method' => $this->faker->paragraph,

        ];
    }
}
