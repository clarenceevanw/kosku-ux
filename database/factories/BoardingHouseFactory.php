<?php

namespace Database\Factories;

use App\Enum\GenderType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BoardingHouse>
 */
class BoardingHouseFactory extends Factory
{
    /**
     * Realistic Indonesian kos data by city.
     * Images sourced from public CDN for demonstration.
     */
    private static array $housesData = [
        // ═══════════════ SURABAYA (primary city, most data) ═══════════════
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos Rungkut Harmoni',
            'address' => 'Jl. Rungkut Mapan Utara No. 12, Rungkut',
            'description' => 'Kos nyaman di kawasan Rungkut, dekat dengan UBAYA dan kawasan industri SIER. Lingkungan tenang, keamanan 24 jam dengan CCTV.',
            'gender_type' => 'campur',
            'latitude' => -7.3218, 'longitude' => 112.7560,
            'postal_code' => '60293',
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos Gubeng Residence',
            'address' => 'Jl. Gubeng Kertajaya VIII No. 5, Gubeng',
            'description' => 'Kos premium di pusat kota Surabaya. Sangat strategis, dekat stasiun Gubeng, pusat perbelanjaan dan perkantoran.',
            'gender_type' => 'putri',
            'latitude' => -7.2753, 'longitude' => 112.7449,
            'postal_code' => '60282',
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos Mulyosari Premium',
            'address' => 'Jl. Mulyosari Utara No. 88, Mulyosari',
            'description' => 'Kos asri di kawasan Mulyosari. Dekat ITS, PENS, dan kampus Unair. Cocok untuk mahasiswa dan karyawan.',
            'gender_type' => 'putra',
            'latitude' => -7.2800, 'longitude' => 112.7950,
            'postal_code' => '60113',
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos Petra Executive',
            'address' => 'Jl. Siwalankerto Timur No. 22, Wonocolo',
            'description' => 'Kos eksklusif dekat Universitas Kristen Petra. Cocok untuk mahasiswa yang menginginkan hunian bersih dan fasilitas lengkap.',
            'gender_type' => 'putri',
            'latitude' => -7.3285, 'longitude' => 112.7225,
            'postal_code' => '60236',
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos ITS Mitra',
            'address' => 'Jl. Arief Rachman Hakim No. 45, Sukolilo',
            'description' => 'Kos strategis tepat di depan ITS Surabaya. Tersedia koneksi WiFi fiber optik dan akses 24 jam.',
            'gender_type' => 'putra',
            'latitude' => -7.2817, 'longitude' => 112.7953,
            'postal_code' => '60117',
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos Menur Pumpungan',
            'address' => 'Jl. Menur Pumpungan No. 15, Sukolilo',
            'description' => 'Kos modern dekat RS Universitas Airlangga dan RSUD dr. Soetomo. Cocok untuk co-ass dan dokter muda.',
            'gender_type' => 'campur',
            'latitude' => -7.2791, 'longitude' => 112.7567,
            'postal_code' => '60119',
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos Darmo Permai',
            'address' => 'Jl. Darmo Permai III No. 7, Dukuh Pakis',
            'description' => 'Kos mewah di kawasan elite Darmo. Dekat pusat bisnis Surabaya Barat, WTC, dan Galaxy Mall.',
            'gender_type' => 'putri',
            'latitude' => -7.2957, 'longitude' => 112.7127,
            'postal_code' => '60226',
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur',
            'name' => 'Kos Manyar Permai',
            'address' => 'Jl. Manyar Kertoadi No. 11, Mulyorejo',
            'description' => 'Hunian asri dan quiet zone di Manyar. Dekat UNAIR kampus C dan akses mudah ke pusat kota.',
            'gender_type' => 'campur',
            'latitude' => -7.2702, 'longitude' => 112.7750,
            'postal_code' => '60115',
        ],
        // ═══════════════ JAKARTA ═══════════════
        [
            'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta',
            'name' => 'Kos Eksklusif Senopati',
            'address' => 'Jl. Senopati No. 81, Kebayoran Baru',
            'description' => 'Kos premium dengan desain modern minimalis di kawasan elite Senopati. Dekat pusat bisnis SCBD dan Sudirman.',
            'gender_type' => 'putri',
            'latitude' => -6.2297, 'longitude' => 106.8097,
            'postal_code' => '12110',
        ],
        [
            'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta',
            'name' => 'Kos Kemang Executive',
            'address' => 'Jl. Kemang Raya No. 12, Kemang',
            'description' => 'Kos bergaya di kawasan Kemang yang artsy dan cosmopolitan. Dekat restoran, cafe, dan pusat hiburan premium.',
            'gender_type' => 'campur',
            'latitude' => -6.2615, 'longitude' => 106.8143,
            'postal_code' => '12730',
        ],
        [
            'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta',
            'name' => 'Kos Nyaman Tebet',
            'address' => 'Jl. Tebet Timur Dalam No. 55, Tebet',
            'description' => 'Kos bersih dan nyaman di Tebet. Dekat taman Tebet Eco Park, stasiun MRT Tebet, dan berbagai kuliner.',
            'gender_type' => 'putri',
            'latitude' => -6.2384, 'longitude' => 106.8459,
            'postal_code' => '12820',
        ],
        [
            'city' => 'Jakarta Pusat', 'province' => 'DKI Jakarta',
            'name' => 'Kos Menteng Asri',
            'address' => 'Jl. Gondangdia Lama No. 30, Menteng',
            'description' => 'Kos heritage di kawasan Menteng yang bersejarah. Lingkungan tenang dengan bangunan klasik yang terawat.',
            'gender_type' => 'campur',
            'latitude' => -6.1980, 'longitude' => 106.8384,
            'postal_code' => '10350',
        ],
        // ═══════════════ BANDUNG ═══════════════
        [
            'city' => 'Bandung', 'province' => 'Jawa Barat',
            'name' => 'Studio Apartment Dago',
            'address' => 'Jl. Dago (Ir. H. Juanda) No. 150, Coblong',
            'description' => 'Studio modern di jantung kota Bandung. View gunung dan udara sejuk khas Bandung. Dekat ITB dan Dago Entertainment.',
            'gender_type' => 'campur',
            'latitude' => -6.8726, 'longitude' => 107.6101,
            'postal_code' => '40135',
        ],
        [
            'city' => 'Bandung', 'province' => 'Jawa Barat',
            'name' => 'Kos Minimalis Pasteur',
            'address' => 'Jl. Pasteur No. 22, Sukajadi',
            'description' => 'Kos modern dekat pintu tol Pasteur. Akses mudah ke seluruh Bandung dan cocok untuk karyawan.',
            'gender_type' => 'putra',
            'latitude' => -6.8870, 'longitude' => 107.5876,
            'postal_code' => '40161',
        ],
        // ═══════════════ YOGYAKARTA ═══════════════
        [
            'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta',
            'name' => 'Kos Minimalis Seturan',
            'address' => 'Jl. Seturan Raya No. 56, Depok, Sleman',
            'description' => 'Kos modern di kawasan Seturan Yogyakarta. Dekat UPN, AMIKOM, dan UGM. Harga terjangkau dengan fasilitas lengkap.',
            'gender_type' => 'putra',
            'latitude' => -7.7674, 'longitude' => 110.3970,
            'postal_code' => '55281',
        ],
        [
            'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta',
            'name' => 'Kos Condongcatur',
            'address' => 'Jl. Condongcatur No. 8, Depok, Sleman',
            'description' => 'Kos nyaman di Condongcatur. Lingkungan mahasiswa yang ramai, akses mudah ke berbagai kampus di DIY.',
            'gender_type' => 'putri',
            'latitude' => -7.7493, 'longitude' => 110.3899,
            'postal_code' => '55283',
        ],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $data = static::$housesData[static::$index % count(static::$housesData)];
        static::$index++;

        return [
            'owner_id'     => User::factory()->owner(),
            'name'         => $data['name'],
            'description'  => $data['description'],
            'address'      => $data['address'],
            'city'         => $data['city'],
            'province'     => $data['province'],
            'postal_code'  => $data['postal_code'],
            'latitude'     => $data['latitude'] + $this->faker->randomFloat(4, -0.0005, 0.0005),
            'longitude'    => $data['longitude'] + $this->faker->randomFloat(4, -0.0005, 0.0005),
            'gender_type'  => $data['gender_type'],
        ];
    }

    /** State: filter to Surabaya */
    public function surabaya(): static
    {
        return $this->state(function (array $attributes) {
            $sbyData = array_values(array_filter(
                static::$housesData,
                fn ($d) => $d['city'] === 'Surabaya'
            ));
            $pick = $sbyData[array_rand($sbyData)];
            return [
                'name'        => $pick['name'],
                'description' => $pick['description'],
                'address'     => $pick['address'],
                'city'        => $pick['city'],
                'province'    => $pick['province'],
                'postal_code' => $pick['postal_code'],
                'latitude'    => $pick['latitude'],
                'longitude'   => $pick['longitude'],
                'gender_type' => $pick['gender_type'],
            ];
        });
    }

    public static function getAllHousesData(): array
    {
        return static::$housesData;
    }
}
