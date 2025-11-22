<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@imanagement.com',
            'password' => Hash::make('password'),
            'role' => 'SUPERADMIN',
            'roles' => ['SUPERADMIN'],
            'active_flag' => true,
            'status' => 'ACTIVE',
            'created_id' => 1,
        ]);

        // You can add more default users here
        User::create([
            'name' => 'Admin Pusat',
            'username' => 'adminpusat',
            'email' => 'adminpusat@imanagement.com',
            'password' => Hash::make('password'),
            'role' => 'SUPERADMIN',
            'roles' => ['SUPERADMIN'],
            'active_flag' => true,
            'status' => 'ACTIVE',
            'created_id' => 1,
        ]);
    }
}
