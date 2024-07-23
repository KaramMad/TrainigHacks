<?php

namespace Database\Seeders;

use App\Models\ColorProduct;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(20)->create();
        foreach(Product::all() as $product){
            $size=ProductSize::take(rand(1,4))->pluck('id');
            $color=ColorProduct::take(rand(1,8))->pluck('id');
            $product->sizes()->attach($size);
            $product->colors()->attach($color);
        }
    }
}
