<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
use Illuminate\Support\Facades\DB;
class ExerciseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $name = GlobalVariables::FastWorkout();
        $image=GlobalVariables::FastWorkout_Images();
        $description=GlobalVariables::FastWorkout_Description();
        for($i=0;$i<3;$i++){
            DB::table('exercise_types')->insert([
                'name'=>$name[$i],
                'image'=>$image[$i],
                'description'=>$description[$i],
            ]);
        }
    }
}
