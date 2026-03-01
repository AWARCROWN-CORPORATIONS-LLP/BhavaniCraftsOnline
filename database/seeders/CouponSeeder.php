<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Coupon::create([
            'code' => 'DIVINE10',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 500,
            'usage_limit' => 100,
            'expires_at' => now()->addMonths(6),
            'status' => true,
        ]);

        \App\Models\Coupon::create([
            'code' => 'WELCOME20',
            'type' => 'percentage',
            'value' => 20,
            'min_order_amount' => 1000,
            'usage_limit' => 50,
            'expires_at' => now()->addMonths(3),
            'status' => true,
        ]);

        \App\Models\Coupon::create([
            'code' => 'SAVE500',
            'type' => 'fixed',
            'value' => 500,
            'min_order_amount' => 2000,
            'usage_limit' => 20,
            'expires_at' => now()->addMonths(1),
            'status' => true,
        ]);
    }
}
