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
        Schema::create('poojari_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('specializations')->nullable(); // comma separated or JSON
            $table->string('profile_image')->nullable();
            $table->json('availability')->nullable(); // format: {"Monday": ["09:00-12:00", "14:00-17:00"], ...}
            $table->string('location')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('poojari_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who booked
            $table->foreignId('poojari_id')->constrained('users')->onDelete('cascade'); // The Poojari
            $table->string('event_name');
            $table->datetime('event_date');
            $table->text('event_address');
            $table->text('additional_notes')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, employee_contacted, completed, cancelled
            $table->text('admin_employee_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poojari_bookings');
        Schema::dropIfExists('poojari_profiles');
    }
};
