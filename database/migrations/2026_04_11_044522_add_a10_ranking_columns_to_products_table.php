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
            if (!Schema::hasColumn('products', 'sales_velocity_score')) {
                $table->decimal('sales_velocity_score', 10, 4)->default(0)->after('listed_status');
                $table->decimal('authority_score', 10, 4)->default(0)->after('sales_velocity_score');
                $table->decimal('final_a10_score', 10, 4)->default(0)->after('authority_score');
                $table->index('final_a10_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sales_velocity_score', 'authority_score', 'final_a10_score']);
        });
    }
};
