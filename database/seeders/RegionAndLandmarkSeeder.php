<?php

namespace Database\Seeders;

use App\Enum\LandmarkType;
use App\Models\City;
use App\Models\District;
use App\Models\Landmark;
use App\Models\Province;
use Illuminate\Database\Seeder;

class RegionAndLandmarkSeeder extends Seeder
{
    /**
     * BPS-aligned regional data for Jawa Timur → Surabaya.
     * IDs follow the Badan Pusat Statistik (BPS) coding standard:
     *   Province  : 2 chars  (35        = Jawa Timur)
     *   City      : 4 chars  (3578      = Kota Surabaya)
     *   District  : 7 chars  (3578XXX   = Kecamatan in Surabaya)
     *
     * Reference: https://sig.bps.go.id/bridging-prod/index
     */

    // ── Provinces ────────────────────────────────────────────────────
    private array $provinces = [
        ['id' => '35', 'name' => 'Jawa Timur'],
        ['id' => '31', 'name' => 'DKI Jakarta'],
        ['id' => '32', 'name' => 'Jawa Barat'],
        ['id' => '34', 'name' => 'DI Yogyakarta'],
    ];

    // ── Cities ───────────────────────────────────────────────────────
    private array $cities = [
        ['id' => '3578', 'province_id' => '35', 'name' => 'Surabaya'],
        ['id' => '3171', 'province_id' => '31', 'name' => 'Jakarta Selatan'],
        ['id' => '3173', 'province_id' => '31', 'name' => 'Jakarta Pusat'],
        ['id' => '3273', 'province_id' => '32', 'name' => 'Bandung'],
        ['id' => '3404', 'province_id' => '34', 'name' => 'Sleman'],
    ];

    // ── Districts (Kecamatan) ────────────────────────────────────────
    private array $districts = [
        // Surabaya
        ['id' => '3578010', 'city_id' => '3578', 'name' => 'Wonocolo'],
        ['id' => '3578020', 'city_id' => '3578', 'name' => 'Rungkut'],
        ['id' => '3578030', 'city_id' => '3578', 'name' => 'Gubeng'],
        ['id' => '3578040', 'city_id' => '3578', 'name' => 'Dukuh Pakis'],
        ['id' => '3578050', 'city_id' => '3578', 'name' => 'Sukolilo'],
        ['id' => '3578060', 'city_id' => '3578', 'name' => 'Mulyorejo'],
        ['id' => '3578070', 'city_id' => '3578', 'name' => 'Tenggilis Mejoyo'],
        ['id' => '3578080', 'city_id' => '3578', 'name' => 'Sawahan'],
        ['id' => '3578090', 'city_id' => '3578', 'name' => 'Genteng'],
        ['id' => '3578100', 'city_id' => '3578', 'name' => 'Tegalsari'],
        // Jakarta Selatan
        ['id' => '3171010', 'city_id' => '3171', 'name' => 'Kebayoran Baru'],
        ['id' => '3171020', 'city_id' => '3171', 'name' => 'Kemang'],
        ['id' => '3171030', 'city_id' => '3171', 'name' => 'Tebet'],
        // Jakarta Pusat
        ['id' => '3173010', 'city_id' => '3173', 'name' => 'Menteng'],
        // Bandung
        ['id' => '3273010', 'city_id' => '3273', 'name' => 'Coblong'],
        ['id' => '3273020', 'city_id' => '3273', 'name' => 'Sukajadi'],
        // Sleman (Yogyakarta)
        ['id' => '3404010', 'city_id' => '3404', 'name' => 'Depok'],
    ];

    // ── Landmarks ────────────────────────────────────────────────────
    private array $landmarks = [
        [
            'district_id' => '3578010', // Wonocolo
            'name'        => 'Universitas Kristen Petra',
            'type'        => LandmarkType::CAMPUS,
            'latitude'    => -7.3385,
            'longitude'   => 112.7390,
        ],
        [
            'district_id' => '3578070', // Tenggilis Mejoyo
            'name'        => 'Universitas Surabaya (UBAYA)',
            'type'        => LandmarkType::CAMPUS,
            'latitude'    => -7.3213,
            'longitude'   => 112.7681,
        ],
        [
            'district_id' => '3578030', // Gubeng
            'name'        => 'Stasiun Gubeng',
            'type'        => LandmarkType::STATION,
            'latitude'    => -7.2654,
            'longitude'   => 112.7520,
        ],
        [
            'district_id' => '3578050', // Sukolilo
            'name'        => 'Institut Teknologi Sepuluh Nopember (ITS)',
            'type'        => LandmarkType::CAMPUS,
            'latitude'    => -7.2819,
            'longitude'   => 112.7947,
        ],
        [
            'district_id' => '3578060', // Mulyorejo
            'name'        => 'Universitas Airlangga (UNAIR)',
            'type'        => LandmarkType::CAMPUS,
            'latitude'    => -7.2776,
            'longitude'   => 112.7683,
        ],
        [
            'district_id' => '3578040', // Dukuh Pakis
            'name'        => 'WTC Surabaya',
            'type'        => LandmarkType::MALL,
            'latitude'    => -7.2948,
            'longitude'   => 112.7236,
        ],
        [
            'district_id' => '3578090', // Genteng
            'name'        => 'Tunjungan Plaza',
            'type'        => LandmarkType::MALL,
            'latitude'    => -7.2650,
            'longitude'   => 112.7403,
        ],
        [
            'district_id' => '3578100', // Tegalsari
            'name'        => 'Stasiun Surabaya Kota (Semut)',
            'type'        => LandmarkType::STATION,
            'latitude'    => -7.2502,
            'longitude'   => 112.7368,
        ],
    ];

    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🗺️  Seeding regional data (3NF) and landmarks...');

        // ── Provinces ──────────────────────────────────────────────
        foreach ($this->provinces as $data) {
            Province::updateOrCreate(['id' => $data['id']], $data);
        }
        $this->command->info('  ✓ Provinces: ' . count($this->provinces));

        // ── Cities ────────────────────────────────────────────────
        foreach ($this->cities as $data) {
            City::updateOrCreate(['id' => $data['id']], $data);
        }
        $this->command->info('  ✓ Cities: ' . count($this->cities));

        // ── Districts ─────────────────────────────────────────────
        foreach ($this->districts as $data) {
            District::updateOrCreate(['id' => $data['id']], $data);
        }
        $this->command->info('  ✓ Districts: ' . count($this->districts));

        // ── Landmarks ─────────────────────────────────────────────
        foreach ($this->landmarks as $data) {
            Landmark::updateOrCreate(
                ['name' => $data['name'], 'district_id' => $data['district_id']],
                [
                    'district_id' => $data['district_id'],
                    'name'        => $data['name'],
                    'type'        => $data['type']->value,
                    'latitude'    => $data['latitude'],
                    'longitude'   => $data['longitude'],
                ]
            );
        }
        $this->command->info('  ✓ Landmarks: ' . count($this->landmarks));

        $this->command->info('✅ Regional & landmark seeding complete!');
        $this->command->info('');
    }
}
