<?php

namespace Database\Seeders;

use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuscleAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $muscle_name = GlobalVariables::muscleArea();
        $women_image = GlobalVariables::muscleArea_image_women();
        $men_image = GlobalVariables::muscleArea_image_men();
        for ($i = 0; $i < 5; $i++) {
            DB::table('muscles')->insert([
                'muscle_area' => $muscle_name[$i],
                'men_image'=>$men_image[$i],
                'women_image'=>$women_image[$i],
            ]);
        }
    }
}
