<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Divine Brass Idols',
            'Pooja Mandir Essentials',
            'Festive Decoration Artifacts',
            'Spiritual Incense & Aromas',
            'Custom Handcrafted Gifts',
            'Wall Hanging Artifacts',
            'Handwoven Silk Accessories'
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
