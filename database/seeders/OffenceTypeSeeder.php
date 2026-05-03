<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OffenceType;

class OffenceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $offences = [
            ['name' => 'No valid vehicle sticker', 'amount' => 50.00],
            ['name' => 'Parking in prohibited area', 'amount' => 30.00],
            ['name' => 'Blocking entrance / emergency lane', 'amount' => 100.00],
            ['name' => 'Parking in staff reserved area', 'amount' => 30.00],
            ['name' => 'Double parking', 'amount' => 50.00],
        ];

        foreach ($offences as $offence) {
            OffenceType::create($offence);
        }
    }
}