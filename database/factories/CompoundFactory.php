<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\OffenceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompoundFactory extends Factory
{
    public function definition(): array
    {
        $issuedAt = $this->faker->dateTimeBetween('2026-04-20', '2026-06-10');
        $status   = $this->faker->randomElement(['unpaid', 'unpaid', 'paid', 'appealing', 'resolved']);

        $offenceTypeId = OffenceType::inRandomOrder()->value('id') 
                         ?? OffenceType::create(['name' => 'General Campus Offence', 'amount' => 50.00])->id;

        return [
            'vehicle_id'      => Vehicle::where('status', 'approved')->inRandomOrder()->value('id') 
                                 ?? Vehicle::factory()->create(['status' => 'approved'])->id,
                                 
            'officer_id'      => User::where('role', 'officer')->inRandomOrder()->value('id') 
                                 ?? User::factory()->create(['role' => 'officer'])->id,
                                 
            'offence_type_id' => $offenceTypeId,
            'status'          => $status,
            'is_discounted'   => false,
            'issued_at'       => $issuedAt,
            'paid_at'         => $status === 'paid' ? $this->faker->dateTimeBetween($issuedAt, '2026-06-10') : null,
        ];
    }
}