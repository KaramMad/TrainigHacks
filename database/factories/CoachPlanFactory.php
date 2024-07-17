<?php

namespace Database\Factories;

use App\Models\coachPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\coachPlan>
 */
class CoachPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $plan=coachPlan::class;
    public function definition(): array
    {
        return [
            'coach_id'=>$this->faker->randomElement([1,2,3,4]),
            'plan_name'=>$this->faker->word(),
            'description'=>$this->faker->paragraph(),
        ];
    }
}
