<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CatproductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = GlobalVariables::product_category();
        $image=GlobalVariables::category_porduct_image();
        for($i=0;$i<3;$i++){
            DB::table('catproducts')->insert([
                'category_name'=>$category[$i],
                'image'=>$image[$i],
                'description'=>'shoso shoso',
            ]);
        }
    }
}
