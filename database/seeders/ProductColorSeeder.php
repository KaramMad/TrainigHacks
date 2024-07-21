<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
class ProductColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $color = GlobalVariables::product_color();

        for($i=0;$i<8;$i++){
            DB::table('color_products')->insert([
                'color'=>$color[$i],
            ]);
        }
    }
}
