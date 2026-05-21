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
use Database\Factories\FacilityFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('reviews')->truncate();
        DB::table('contracts')->truncate();
        DB::table('transactions')->truncate();
        DB::table('room_facility')->truncate();
        DB::table('boarding_house_facility')->truncate();
        DB::table('rooms')->truncate();
        DB::table('boarding_houses')->truncate();
        DB::table('facilities')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            FacilitySeeder::class,
            UserAndBoardingHouseSeeder::class,
        ]);
    }
}
