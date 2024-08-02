<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $exercise=Exercise::class;
    public function definition(): array
    {
        return [
            'exercise_type_id'=>$this->faker->randomElement(['1','2','3',null]),
            'exercise_name' => $this->faker->word,
            'description' => $this->faker->paragraph(2),
            'calories' =>$this->faker->numberBetween(1,130),
            'time'=>$this->faker->numberBetween(10,60),
            'reps'=>$this->faker->numberBetween(10,12),
            'gif'=>'Exercises/jumping jacks.gif',
            'video_link'=>'https://youtu.be/QE5zcHWGjCM?si=FZeMwXLACqBIvkjC',
            'target'=>$this->faker->randomElement(['lose_weight','build_muscle','keep_fit']),
            'level'=>$this->faker->randomElement(['beginner','intermediate','advanced']),
            'gender'=>$this->faker->randomElement(['male','female']),
            'choose'=>$this->faker->randomElement(['equipment','no_equipment']),
            'private'=>$this->faker->randomElement([0,1]),
            'diseases'=>$this->faker->randomElement(['heart','none','knee','breath']),
        ];
    }
}
