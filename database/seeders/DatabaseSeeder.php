<?php

namespace Database\Seeders;

use App\Enum\ContractStatus;
use App\Enum\PaymentStatus;
use App\Enum\UserRole;
use App\Models\BoardingHouse;
use App\Models\Contract;
use App\Models\Facility;
use App\Models\Review;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
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

        DB::table('room_facility')->truncate();
        DB::table('boarding_house_facility')->truncate();
        DB::table('boarding_house_rules')->truncate();  // pivot (now rule_id based)
        DB::table('rooms')->truncate();
        DB::table('boarding_houses')->truncate();
        DB::table('facilities')->truncate();
        DB::table('rules')->truncate();                 // master rules table
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            FacilitySeeder::class,              // 1. master facilities (with type)
            RuleSeeder::class,                  // 2. master rules
            UserAndBoardingHouseSeeder::class,  // 3. houses + pivot attachments
        ]);
    }
}
