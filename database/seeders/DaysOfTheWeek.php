<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DaysOfTheWeek extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            ['day' => 'Sunday'],
            ['day' => 'Monday'],
            ['day' => 'Tuesday'],
            ['day' => 'Wednesday'],
            ['day' => 'Thursday'],
            ['day' => 'Friday'],
            ['day' => 'Saturday'],
        ];

        DB::table('training_days')->insert($days);
    }
}
