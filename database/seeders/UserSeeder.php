<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => 'admin',
                'password' => Hash::make('12345678'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Cafe Manager',
                'role' => 'manager',
                'password' => Hash::make('12345678'),
            ],
        );

        User::updateOrCreate(
            ['email' => 'cashier@example.com'],
            [
                'name' => 'Cafe Cashier',
                'role' => 'cashier',
                'password' => Hash::make('12345678'),
            ],
        );
    }
}
