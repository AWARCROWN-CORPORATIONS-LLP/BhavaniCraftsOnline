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
        Schema::table('quick_bills', function (Blueprint $table) {
            $table->boolean('is_quotation')->default(false)->after('bill_number');
            $table->decimal('discount_amount', 15, 2)->default(0.00)->after('subtotal');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quick_bills', function (Blueprint $table) {
            //
        });
    }
};
