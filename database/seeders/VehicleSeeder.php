<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $randomUsers = User::where('role', 'user')
            ->inRandomOrder()
            ->take(20)
            ->get();

        foreach ($randomUsers as $user) {
            Vehicle::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}