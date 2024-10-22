<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Articl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Admin::factory()->create([
            'name' => 'karam',
            'phone_number' => '0943365119',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password
        ]);
        // \App\Models\Coach::create([
        //     'name' => 'karam',
        //     'phone_number' => '0943365119',
        //     'bio' => 'fight for your success',
        //     'description' => 'funny',
        //     'age' => '21',
        //     'price' => '5000',
        //     'image' => 'Profiles/Ammis.jpg',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password
        // ]);
        // \App\Models\User::create([
        //     'name' => 'karam',
        //     'email' => 'karamalmadne@gmail.com',
        //     'target' => 'build_muscle',
        //     'level' => 'beginner',
        //     'weight'=>'85',
        //     'tall'=>'174',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password
        // ]);
        $this->call(ExerciseTypeSeeder::class);
       // $this->call([ChallengeSeeder::class]);
       // $this->call([ArticleSeeder::class]);
        $this->call([FocusAreaSeeder::class]);
        $this->call([DaysOfTheWeek::class]);
        $this->call([MuscleAreaSeeder::class]);
        $this->call([MuscleCategorySeeder::class]);
        $this->call([MuscleLevelSeeder::class]);
        ///$this->call(CoachSeeder::class);
       // $this->call(CoachPlanSeeder::class);
        // \App\Models\coachPlan::create([
        //     'plan_name' => 'buildMuscle',
        //     'coach_id' => 1,
        //     'description' => 'love is all love',
        //     'target' => 'build_muscle',
        //     'level' => 'beginner',
        //     'choose' => 'no_equipment'
        // ]);
       // $this->call(ExerciseSeeder::class);
        //$this->call(IngredientSeeder::class);
        //$this->call(MealSeeder::class);
        $this->call([CatproductSeeder::class, ProductColorSeeder::class, ProductSizeSeeder::class]);
        $this->call([SubCatgroiesSeeder::class]);//, ProductSeeder::class
        // for ($i = 0; $i < 5; $i++) {

        //     \App\Models\Poster::create([
        //         'image' => 'ProductPosters/untitled.jpg',
        //     ]);
        // }
    }
}
