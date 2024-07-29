<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Providers\GlobalVariablesServiceProvider as GlobalVariables;
use Illuminate\Support\Facades\DB;
class SubCatgroiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $subCat = GlobalVariables::sub_Categories();
        //
        for($i=0;$i<5;$i++){
            DB::table('catproducts')->insert([
                'category_name'=>$subCat[$i],
                'parent_id'=>'1',
                'image'=>null,
                'description'=>'.',
            ]);
        }
    }
}
