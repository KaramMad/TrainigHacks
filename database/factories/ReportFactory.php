<?php

namespace Database\Factories;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $report = Report::class;
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'report_date' => Carbon::now()->startOfWeek()->addDays($this->faker->unique()->numberBetween(0, 6)),
            'calories' => $this->faker->numberBetween(2000, 3000),
            'steps' => $this->faker->numberBetween(5000, 10000),
            'Number_of_exercises' => $this->faker->numberBetween(1, 5),
            'time' => gmdate("H:i:s", $this->faker->numberBetween(3600, 7200)),
            'total_exercises' => $this->faker->numberBetween(100, 200),
            'total_calories' => $this->faker->numberBetween(2000, 3000),
            'total_time' => gmdate("H:i:s", $this->faker->numberBetween(3600, 7200)),

        ];
    }
}
