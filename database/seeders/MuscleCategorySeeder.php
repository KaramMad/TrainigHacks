<?php

namespace Database\Seeders;

use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuscleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $muscle_category = GlobalVariables::muscleCategory();
        for ($i = 0; $i < 5; $i++) {
            DB::table('categories')->insert(['category_name' => $muscle_category[$i]]);
        }
    }
}
