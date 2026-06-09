<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Compound;

class CompoundSeeder extends Seeder
{
    public function run(): void
    {
        Compound::factory(50)->create();
    }
}