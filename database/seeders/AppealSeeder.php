<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appeal;
use App\Models\Compound;

class AppealSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there are at least 5 compounds with 'appealing' status for demo purposes
        $appealingCompounds = Compound::where('status', 'appealing')->get();

        if ($appealingCompounds->count() < 5) {
            $neededCount = 5 - $appealingCompounds->count();

            $compoundsToConvert = Compound::where('status', 'unpaid')
                ->inRandomOrder()
                ->take($neededCount)
                ->get();

            foreach ($compoundsToConvert as $compound) {
                $compound->update(['status' => 'appealing']);
            }

            $appealingCompounds = Compound::where('status', 'appealing')->get();
        }

        foreach ($appealingCompounds as $compound) {
            Appeal::factory()->create([
                'compound_id' => $compound->id,
            ]);
        }
    }
}