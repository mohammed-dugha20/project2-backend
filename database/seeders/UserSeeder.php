<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersToSeed = [
            [
                'username' => 'main_admin',
                'name' => 'Main Admin',
                'email' => 'admin@example.com',
                'phone' => '1111111111',
                'password' => Hash::make('password'),
                'user_type' => 'platform_admin',
            ],
            [
                'username' => 'customer_user',
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'phone' => '2222222222',
                'password' => Hash::make('password'),
                'user_type' => 'customer',
            ],
            [
                'username' => 'realestate_office_user',
                'name' => 'Real Estate Office User',
                'email' => 'office@example.com',
                'phone' => '3333333333',
                'password' => Hash::make('password'),
                'user_type' => 'real_estate_office',
            ],
            [
                'username' => 'finishing_co_user',
                'name' => 'Finishing Co User',
                'email' => 'finishing@example.com',
                'phone' => '4444444444',
                'password' => Hash::make('password'),
                'user_type' => 'finishing_company',
            ],
            [
                'username' => 'service_provider_user',
                'name' => 'Service Provider User',
                'email' => 'provider@example.com',
                'phone' => '5555555555',
                'password' => Hash::make('password'),
                'user_type' => 'service_provider',
            ],
        ];

        foreach ($usersToSeed as $userData) {
            User::create($userData);
        }
    }
}
