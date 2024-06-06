<?php

namespace Database\Factories;
use App\Models\MealType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MealType>
 */
class MealTypeFactory extends Factory
{
    protected $model = MealType::class;

    public function definition(): array
    {
        return [
        'name' => $this->faker->word,
        ];
    }
}
