<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Exercise;
use App\Models\FocusArea;
use App\Models\Muscle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Exercise::factory()->count(20)->create();
        foreach(Exercise::all() as $exercise){
            $focusArea=FocusArea::inRandomOrder()->take(rand(1,5))->pluck('id');
            $category=Category::inRandomOrder()->take(rand(1,5))->pluck('id');
            $muscle=Muscle::inRandomOrder()->take(rand(1,5))->pluck('id');
            $exercise->focusAreas()->attach($focusArea);
            $exercise->categories()->attach($category);
            $exercise->muscles()->attach($muscle);
        }
    }
}
