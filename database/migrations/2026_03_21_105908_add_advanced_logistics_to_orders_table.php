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
            $table->string('delivery_photo_url')->nullable()->after('delivery_pin');
            $table->string('failed_delivery_reason')->nullable()->after('delivery_photo_url');
            $table->integer('delivery_rating')->nullable()->after('failed_delivery_reason');
            $table->decimal('delivery_latitude', 10, 8)->nullable()->after('delivery_rating');
            $table->decimal('delivery_longitude', 11, 8)->nullable()->after('delivery_latitude');
            $table->timestamp('return_requested_at')->nullable()->after('delivered_at');
            $table->string('return_reason')->nullable()->after('return_requested_at');
            $table->timestamp('returned_at', precision: 0)->nullable()->after('return_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_photo_url', 'failed_delivery_reason', 'delivery_rating', 
                'delivery_latitude', 'delivery_longitude', 'return_requested_at', 
                'return_reason', 'returned_at'
            ]);
        });
    }
};
