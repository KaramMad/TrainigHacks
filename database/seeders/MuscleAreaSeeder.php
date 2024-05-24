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
        for ($i = 0; $i < 5; $i++) {
            DB::table('muscles')->insert(['muscle_area' => $muscle_name[$i]]);
        }
    }
}
