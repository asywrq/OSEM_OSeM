<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        $letters = strtoupper($this->faker->lexify('???'));
        $numbers = $this->faker->numberBetween(1000, 9999);
        
        return [
            'user_id'   => User::where('role', 'user')->inRandomOrder()->value('id') 
                           ?? User::factory()->create(['role' => 'user'])->id,
                           
            'plate_no'  => $letters . ' ' . $numbers,
            'type'      => $this->faker->randomElement(['car', 'motorcycle', 'van']),
            'reason'    => $this->faker->randomElement([
                'Daily commute to campus',
                'Attending classes',
                'Staff work vehicle',
                'Part-time student commute',
                'Research purposes',
                'Medical appointment access',
                'Health condition accommodation',
                'Campus event participation',
                'Internship commute'
            ]),

            'status'    => $this->faker->randomElement(['pending', 'approved', 'approved', 'approved', 'rejected']),
            'is_active' => true,
            'created_at' => $this->faker->dateTimeBetween('2026-04-20', '2026-06-10'),
        ];
    }
}