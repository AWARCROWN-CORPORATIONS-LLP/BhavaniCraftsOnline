<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add Associate Admin role
        Role::firstOrCreate(['name' => 'associate_admin']);

        // 2. Remove Poojari role
        $poojari = Role::where('name', 'poojari')->first();
        if ($poojari) {
            // Detach users from this role first - Correct table name is user_roles
            \DB::table('user_roles')->where('role_id', $poojari->id)->delete();
            $poojari->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::where('name', 'associate_admin')->delete();
        Role::firstOrCreate(['name' => 'poojari']);
    }
};
