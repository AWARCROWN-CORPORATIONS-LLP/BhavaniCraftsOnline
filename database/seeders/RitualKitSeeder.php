<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RitualKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kit1 = \App\Models\RitualKit::create([
            'name' => 'The Vedic Sandhya Kit',
            'slug' => 'vedic-sandhya-kit',
            'description' => 'A complete selection of hand-carved wooden and brass items for daily Sandhyavandanam rituals.',
            'price' => 4999.00,
            'is_active' => true,
        ]);
        $kit1->products()->attach([1, 2, 3]);

        $kit2 = \App\Models\RitualKit::create([
            'name' => 'Home Puja Starter Box',
            'slug' => 'home-puja-starter',
            'description' => 'The essential brassware and ritual items to start your daily home puja traditions with grace.',
            'price' => 2999.00,
            'is_active' => true,
        ]);
        $kit2->products()->attach([4, 5]);
    }
}
