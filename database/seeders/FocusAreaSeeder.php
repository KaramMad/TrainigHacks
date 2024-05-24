<?php

namespace Database\Seeders;

use App\Models\FocusArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FocusAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $focus_area = GlobalVariables::focusArea();
        for ($i = 0; $i < 10; $i++) {
            DB::table('focus_areas')->insert(['focus_area' => $focus_area[$i]]);
        }
    }
}
