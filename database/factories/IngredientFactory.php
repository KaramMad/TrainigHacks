<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    //protected $ingredient=Ingredient::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'image'=>'Ingredients/1719780198.png',
        ];
    }
}
