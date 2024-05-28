<?php

namespace Database\Seeders;

use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MuscleLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $level = GlobalVariables::muscleLevel();
        $women_image = GlobalVariables::muscleArea_image_women();
        $men_image = GlobalVariables::muscleArea_image_men();
        $k=0;
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 0; $j <3; $j++) {
                DB::table('muscle_levels')->insert([
                    'level' => $level[$j],
                    'men_image' => $men_image[$j+$k],
                    'women_image' => $women_image[$j+$k],
                    'muscle_id'=>$i,
                ]);
            }
            $k+=3;
        }
    }
}
