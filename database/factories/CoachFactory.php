<?php

namespace Database\Factories;

use App\Models\Coach;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coach>
 */
class CoachFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $coach=Coach::class;
    public function definition(): array
    {
        return [
            'name'=>$this->faker->word(),
            'phone_number'=>'09'.(string)mt_rand(10000000,99999999),
            'bio'=>$this->faker->paragraph(),
            'age'=>'25',
            'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'image'=>'Profiles/Ammis.jpg',
            'description'=>$this->faker->paragraph(),
            'price'=>mt_rand(100,999),
        ];
    }
}
