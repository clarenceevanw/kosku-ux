<?php

namespace Database\Seeders;

use App\Models\Facility;
use Database\Factories\FacilityFactory;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = FacilityFactory::allPredefined();
        foreach ($facilities as $facilityData) {
            Facility::create([
                'name' => $facilityData['name'],
                'icon' => $facilityData['icon'],
            ]);
        }
        $this->command->info('✓ Facilities seeded: ' . count($facilities));
    }
}
