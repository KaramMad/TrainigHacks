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
        $category_image = GlobalVariables::category_image();
        $men_image = GlobalVariables::main_category_image_men();
        $women_image = GlobalVariables::main_category_image_women();
        for ($i = 0; $i < 5; $i++) {
            DB::table('categories')->insert(
                [
                    'category_name' => $muscle_category[$i],
                    'image' => $category_image[$i],
                    'men_image' => $men_image[$i],
                    'women_image' => $women_image[$i],
                ]
            );
        }
    }
}
