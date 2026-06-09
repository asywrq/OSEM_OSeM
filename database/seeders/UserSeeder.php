<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Fixed accounts for demo login and testing
        User::create([
            'name'               => 'Admin OSEM',
            'matric_or_staff_no' => '1000001',
            'email'              => 'admin@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'admin',
            'is_active'          => true,
        ]);

        User::create([
            'name'               => 'Admin Jaafar',
            'matric_or_staff_no' => '1000002',
            'email'              => 'jaafar@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'admin',
            'is_active'          => true,
        ]);

        User::create([
            'name'               => 'Sgt. Razif',
            'matric_or_staff_no' => '2000001',
            'email'              => 'officer@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'officer',
            'is_active'          => true,
        ]);

        User::create([
            'name'               => 'Sgt. Amir',
            'matric_or_staff_no' => '2000002',
            'email'              => 'amir@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'officer',
            'is_active'          => true,
        ]);

        User::create([
            'name'               => 'Sgt. Hafiz',
            'matric_or_staff_no' => '2000003',
            'email'              => 'hafiz@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'officer',
            'is_active'          => true,
        ]);

        User::create([
            'name'               => 'Sgt. Zainal',
            'matric_or_staff_no' => '2000004',
            'email'              => 'zainal@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'officer',
            'is_active'          => true,
        ]);

        User::create([
            'name'               => 'Sgt. Farid',
            'matric_or_staff_no' => '2000005',
            'email'              => 'farid@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'officer',
            'is_active'          => true,
        ]);

        User::create([
            'name'               => 'Ahmad Zaki',
            'matric_or_staff_no' => '3000012',
            'email'              => 'user@osem.com',
            'password'           => Hash::make('password'),
            'role'               => 'user',
            'is_active'          => true,
        ]);

        User::factory(42)->create(['role' => 'user', 'is_active' => true]);
    }
}