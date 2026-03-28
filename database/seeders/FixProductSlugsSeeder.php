<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class FixProductSlugsSeeder extends Seeder
{
    public function run()
    {
        foreach(Product::all() as $product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->product_name) . '-' . uniqid();
                $product->save();
            }
        }
    }
}
