<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Users Table: Optimized for dashboard stats and filtering
        Schema::table('users', function (Blueprint $table) {
            $this->addIndexIfMissing('users', 'user_type', 'users_user_type_index', $table);
            $this->addIndexIfMissing('users', 'is_approved', 'users_is_approved_index', $table);
            $this->addIndexIfMissing('users', 'is_blocked', 'users_is_blocked_index', $table);
        });

        // Orders Table: Optimized for revenue tracking and delivery monitoring
        Schema::table('orders', function (Blueprint $table) {
            $this->addIndexIfMissing('orders', 'payment_status', 'orders_payment_status_index', $table);
            $this->addIndexIfMissing('orders', 'delivery_status', 'orders_delivery_status_index', $table);
            $this->addIndexIfMissing('orders', 'created_at', 'orders_created_at_index', $table);
        });

        // Products Table: Optimized for inventory management and low stock alerts
        Schema::table('products', function (Blueprint $table) {
            $this->addIndexIfMissing('products', 'stock', 'products_stock_index', $table);
            $this->addIndexIfMissing('products', 'stock_threshold', 'products_stock_threshold_index', $table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['user_type']);
            $table->dropIndex(['is_approved']);
            $table->dropIndex(['is_blocked']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['delivery_status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['stock']);
            $table->dropIndex(['stock_threshold']);
        });
    }

    private function addIndexIfMissing($tableName, $columnName, $indexName, $table)
    {
        // Simple cross-platform check for index existence
        $indexes = DB::select("SHOW INDEX FROM " . $tableName . " WHERE Key_name = '" . $indexName . "'");
        if (empty($indexes)) {
            $table->index($columnName, $indexName);
        }
    }
};
