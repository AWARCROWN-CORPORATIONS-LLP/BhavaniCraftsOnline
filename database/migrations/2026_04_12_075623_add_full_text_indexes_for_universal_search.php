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
        // Add Full-Text indexes for Products (Update to include product_code)
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasTable('products') && !Schema::hasIndex('products', 'products_code_fulltext')) {
                $table->fullText(['product_code', 'slug'], 'products_code_fulltext');
            }
        });

        // Add Full-Text indexes for Orders
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasTable('orders') && !Schema::hasIndex('orders', 'orders_search_fulltext')) {
                $table->fullText(['order_id_string', 'razorpay_order_id', 'razorpay_payment_id'], 'orders_search_fulltext');
            }
        });

        // Add Full-Text indexes for Categories
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasTable('categories') && !Schema::hasIndex('categories', 'categories_search_fulltext')) {
                $table->fullText(['name', 'slug'], 'categories_search_fulltext');
            }
        });

        // Add Full-Text indexes for QuickBills (Retail Bills)
        Schema::table('quick_bills', function (Blueprint $table) {
            if (Schema::hasTable('quick_bills') && !Schema::hasIndex('quick_bills', 'quick_bills_search_fulltext')) {
                $table->fullText(['bill_number', 'customer_name', 'customer_phone'], 'quick_bills_search_fulltext');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasIndex('products', 'products_code_fulltext')) {
                $table->dropFullText(['product_code', 'slug']);
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasIndex('orders', 'orders_search_fulltext')) {
                $table->dropFullText(['order_id_string', 'razorpay_order_id', 'razorpay_payment_id']);
            }
        });

        Schema::table('categories', function (Blueprint $table) {
             if (Schema::hasIndex('categories', 'categories_search_fulltext')) {
                $table->dropFullText(['name', 'slug']);
             }
        });

        Schema::table('quick_bills', function (Blueprint $table) {
             if (Schema::hasIndex('quick_bills', 'quick_bills_search_fulltext')) {
                $table->dropFullText(['bill_number', 'customer_name', 'customer_phone']);
             }
        });
    }
};
