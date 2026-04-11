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
        // Products Table
        Schema::table('products', function (Blueprint $table) {
            $this->addIndexIfMissing('products', 'slug', 'products_slug_index', $table);
            $this->addIndexIfMissing('products', 'category_id', 'products_category_id_index', $table);
            $this->addIndexIfMissing('products', 'listed_status', 'products_listed_status_index', $table);
        });

        // Orders Table
        Schema::table('orders', function (Blueprint $table) {
            $this->addIndexIfMissing('orders', 'user_id', 'orders_user_id_index', $table);
            $this->addIndexIfMissing('orders', 'status', 'orders_status_index', $table);
        });

        // Cart Items Table
        Schema::table('cart_items', function (Blueprint $table) {
            $this->addIndexIfMissing('cart_items', 'user_id', 'cart_items_user_id_index', $table);
            $this->addIndexIfMissing('cart_items', 'session_id', 'cart_items_session_id_index', $table);
        });
        
        // Product Images Table
        Schema::table('product_images', function (Blueprint $table) {
            $this->addIndexIfMissing('product_images', 'product_id', 'product_images_product_id_index', $table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['listed_status']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['session_id']);
        });
        
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });
    }

    private function addIndexIfMissing($tableName, $columnName, $indexName, $table)
    {
        $indexes = DB::select("SHOW INDEX FROM " . $tableName . " WHERE Key_name = '" . $indexName . "'");
        if (empty($indexes)) {
            $table->index($columnName, $indexName);
        }
    }
};
