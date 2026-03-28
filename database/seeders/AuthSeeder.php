<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Role::truncate();
        DB::table('user_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole      = Role::create(['name' => 'admin']);
        $employeeRole   = Role::create(['name' => 'employee']);
        $franchiseRole  = Role::create(['name' => 'franchise']);
        $customerRole   = Role::create(['name' => 'customer']);
        $poojariRole    = Role::create(['name' => 'poojari']);
        $logisticsRole  = Role::create(['name' => 'logistics']);

        $admin = User::create([
            'id' => 1,
            'username' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'user_type' => 'super_admin',
            'is_approved' => true,
            'is_verified' => true
        ]);
        $admin->roles()->attach($superAdminRole);

        User::create([
            'id' => 2,
            'username' => 'testuser',
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'customer',
            'is_approved' => true,
            'is_verified' => true
        ]);
    }
}
