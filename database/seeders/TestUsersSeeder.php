<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed test users for all roles.
     */
    public function run(): void
    {
        // Test Admin User
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin_test',
                'email' => 'admin@test.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Test Employee User
        User::updateOrCreate(
            ['email' => 'employee@test.com'],
            [
                'name' => 'Employee User',
                'username' => 'employee_test',
                'email' => 'employee@test.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
            ]
        );

        // Test Customer User
        User::updateOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Customer User',
                'username' => 'customer_test',
                'email' => 'customer@test.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
    }
}
