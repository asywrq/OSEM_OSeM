<?php

namespace Database\Factories;

use App\Models\Compound;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppealFactory extends Factory
{
    public function definition(): array
    {
        $compound = Compound::where('status', 'appealing')->inRandomOrder()->first() 
                    ?? Compound::factory()->create(['status' => 'appealing']);

        $result   = $this->faker->randomElement(['pending', 'approved', 'rejected']);

        return [
            'compound_id'  => $compound->id,
            'reviewed_by'  => $result === 'pending' 
                              ? null 
                              : (User::where('role', 'officer')->inRandomOrder()->value('id') 
                                 ?? User::factory()->create(['role' => 'officer'])->id),
                                 
            'reason'       => $this->faker->randomElement([
                'I was not parked there at the time stated.',
                'My vehicle sticker was valid but fell off the windshield.',
                'I had an emergency and had no choice but to park there.',
                'The signage in that area was unclear and misleading.',
                'I was only parked for a few minutes to drop someone off.',
                'I have proof that my sticker application was approved.',
                'I was attending a campus event and thought parking was allowed there.',
                'I haven\'t picked up my vehicle sticker yet but have proof of application submission.',
            ]),
            'result'       => $result,
            
            // Guaranteed to happen after the compound was issued
            'submitted_at' => $this->faker->dateTimeBetween($compound->issued_at, '2026-06-15'),
        ];
    }
}