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


        $this->call(ExerciseTypeSeeder::class);
        $this->call([ChallengeSeeder::class]);
        $this->call([ArticleSeeder::class]);
        $this->call([FocusAreaSeeder::class]);
        $this->call([DaysOfTheWeek::class]);
        $this->call([MuscleAreaSeeder::class]);
        $this->call([MuscleCategorySeeder::class]);
        $this->call([MuscleLevelSeeder::class]);
        $this->call(ExerciseSeeder::class);
        \App\Models\Admin::factory()->create([
            'name'=>'karam',
            'phone_number'=>'0943365119',
            'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password
        ]);
        \App\Models\Coach::create([
            'name'=>'karam',
            'phone_number'=>'0943365119',
            'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password
        ]);
        \App\Models\User::create([
            'name'=>'karam',
            'email'=>'karamalmadne@gmail.com',
            'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password
        ]);

    }
}
