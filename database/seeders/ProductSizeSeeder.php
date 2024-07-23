<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $size = GlobalVariables::product_size();
        for ($i = 0; $i < 5; $i++) {
            DB::table('size_products')->insert(
                ['size' => $size[$i],]
            );
        }
    }
}
