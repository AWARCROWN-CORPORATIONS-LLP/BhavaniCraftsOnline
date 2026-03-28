<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'created_at')) {
                $table->timestamps();
            }
            if (!Schema::hasColumn('orders', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            }
        });

        // Fix address_id type (no FK issue since addresses.id is already BIGINT)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE orders MODIFY COLUMN address_id BIGINT UNSIGNED');
        }

        // Add timestamps to order_items if missing
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'created_at')) {
                $table->timestamps();
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
            // Drop the existing FK, fix types, re-add FK safely
            try {
                DB::statement('ALTER TABLE order_items DROP FOREIGN KEY order_items_order_fk');
            } catch (\Exception $e) {}
            
            try {
                DB::statement('ALTER TABLE order_items MODIFY COLUMN order_id BIGINT UNSIGNED');
                DB::statement('ALTER TABLE order_items MODIFY COLUMN product_id BIGINT UNSIGNED');
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
            } catch (\Exception $e) {}
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'razorpay_signature')) {
                $table->dropColumn('razorpay_signature');
            }
        });
    }
};
