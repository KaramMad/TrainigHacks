<?php

namespace Database\Factories;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $challenge=Challenge::class;
    public function definition(): array
    {
        return [
            'challenge_name'=>$this->faker->word(),
            'timer'=>'30',
            'type'=>'timer',
            'image'=>'Challenge/images.png',
            'gif'=>'Challenge/jumping jacks.gif',
            'secondry_image'=>'Challenge/images.png',
        ];
    }
}
