<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Speed up basic filtering
            if (!Schema::hasIndex('products', 'products_category_id_index')) {
                $table->index('category_id');
            }
            if (!Schema::hasIndex('products', 'products_listed_status_index')) {
                $table->index('listed_status');
            }
            if (!Schema::hasIndex('products', 'products_price_index')) {
                $table->index('price');
            }
            
            // Fulltext for efficient searching (MySQL 5.6+ / MariaDB 10.0.5+)
            if (!Schema::hasIndex('products', 'products_search_fulltext')) {
                $table->fullText(['product_name', 'short_description', 'material_type', 'festival_use'], 'products_search_fulltext');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['listed_status']);
            $table->dropIndex(['price']);
            $table->dropIndex('products_search_fulltext');
        });
    }
};
