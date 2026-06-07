<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate in reverse dependency order
        DB::table('reviews')->truncate();
        DB::table('contracts')->truncate();
        DB::table('monthly_payments')->truncate();

        DB::table('room_facility')->truncate();
        DB::table('boarding_house_facility')->truncate();
        DB::table('boarding_house_rules')->truncate();
        DB::table('rooms')->truncate();
        DB::table('boarding_houses')->truncate();

        DB::table('landmarks')->truncate();
        DB::table('districts')->truncate();
        DB::table('cities')->truncate();
        DB::table('provinces')->truncate();

        DB::table('facilities')->truncate();
        DB::table('rules')->truncate();
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            RegionAndLandmarkSeeder::class,     // 1. provinces → cities → districts → landmarks
            FacilitySeeder::class,              // 2. master facilities (with type)
            RuleSeeder::class,                  // 3. master rules
            UserAndBoardingHouseSeeder::class,  // 4. houses + pivot attachments (uses district_id)
        ]);
    }
}
