<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin OSEM',
            'matric_or_staff_no' => 'ADMIN001',
            'email' => 'admin@osem.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Sgt. Razif',
            'matric_or_staff_no' => 'OFF001',
            'email' => 'officer@osem.com',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ahmad Zaki',
            'matric_or_staff_no' => 'A21EC0012',
            'email' => 'user@osem.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);
    }
}