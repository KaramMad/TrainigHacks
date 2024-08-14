<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $product=Product::class;
    public function definition(): array
    {
        return [
            'name'=>$this->faker->word(),
            'description'=>$this->faker->paragraph(),
            'price'=>$this->faker->numberBetween(1,10),
            'stock'=>$this->faker->numberBetween(1,100),
            'brand'=>$this->faker->randomElement(['nike','adidas']),
            'image'=>'Products/Products.png',
            'weight'=>$this->faker->randomElement(['null','29','5']),
            'measuring_unit'=>$this->faker->randomElement(['kg','g','pounds']),
            'protein'=>$this->faker->randomElement(['null','19']),
            'creatine'=>$this->faker->randomElement(['null','50']),
            'expiration_date' => $this->faker->dateTimeBetween('now','+3 years'),
            'sales_count'=>$this->faker->numberBetween(1,100),
            'view_count'=>$this->faker->numberBetween(1,100),

        ];
    }
}
