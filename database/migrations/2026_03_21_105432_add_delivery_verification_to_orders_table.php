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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_pin', 10)->nullable()->after('dispatch_id');
            $table->integer('pin_generations_count')->default(0)->after('delivery_pin');
            $table->timestamp('delivered_at')->nullable()->after('pin_generations_count');
            $table->enum('delivery_status', ['Pending', 'In Transit', 'Out for Delivery', 'Delivered', 'Returned', 'Failed'])->default('Pending')->after('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_pin', 'pin_generations_count', 'delivered_at', 'delivery_status']);
        });
    }
};
