<?php

namespace Database\Seeders;

use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master Administrator
        $admin = User::updateOrCreate(
            ['email' => 'admin@99bulksales.my'],
            [
                'name' => 'Store Administrator',
                'phone' => '+60128899900',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('Admin@12345!'),
            ]
        );

        // 2. Staff Member
        User::updateOrCreate(
            ['email' => 'staff@99bulksales.my'],
            [
                'name' => 'Dispatch Staff',
                'phone' => '+60123344556',
                'role' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('Staff@12345!'),
            ]
        );

        // 3. Demo Customer
        $customer = User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Nabeel Ahmad',
                'phone' => '+60112233445',
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('Customer@123!'),
            ]
        );

        // Add saved address for demo customer
        CustomerAddress::updateOrCreate(
            ['user_id' => $customer->id, 'label' => 'Restaurant HQ'],
            [
                'recipient_name' => 'Nabeel Ahmad',
                'recipient_phone' => '+60112233445',
                'address_line' => 'No. 18, Jalan BK 5A/2, Bandar Kinrara',
                'area_city' => 'Puchong, Selangor',
                'floor' => 1,
                'landmark' => 'Near Kinrara Golf Club',
                'latitude' => 3.0380,
                'longitude' => 101.6420,
                'is_default' => true,
            ]
        );
    }
}

