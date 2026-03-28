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
        Schema::create('safety_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_logistics_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('complaint_type'); // e.g., Misconduct, Safety Violation, Harassment, etc.
            $table->text('description');
            $table->string('status')->default('Pending'); // Pending, Investigating, Resolved, Dismissed
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_complaints');
    }
};
