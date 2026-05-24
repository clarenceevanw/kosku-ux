<?php

namespace Database\Seeders;

use App\Enum\ContractStatus;
use App\Enum\FacilityType;
use App\Enum\PaymentStatus;
use App\Enum\UserRole;
use App\Models\BoardingHouse;
use App\Models\Contract;
use App\Models\Facility;
use App\Models\Review;
use App\Models\Room;
use App\Models\Rule;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserAndBoardingHouseSeeder extends Seeder
{
    /**
     * Static boarding house data to ensure every unique name is seeded.
     * Mirrors BoardingHouseFactory data but is fully controlled here.
     */
    private array $housesData = [
        // ─── SURABAYA (8 houses) ───────────────────────────────────────────
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60293',
            'name' => 'Kos Rungkut Harmoni',
            'address' => 'Jl. Rungkut Mapan Utara No. 12, Rungkut',
            'description' => 'Kos nyaman di kawasan Rungkut, dekat dengan UBAYA dan kawasan industri SIER. Lingkungan tenang, keamanan 24 jam dengan CCTV.',
            'gender_type' => 'campur', 'latitude' => -7.3218, 'longitude' => 112.7560,
            'images' => ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800', 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800'],
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60282',
            'name' => 'Kos Gubeng Residence',
            'address' => 'Jl. Gubeng Kertajaya VIII No. 5, Gubeng',
            'description' => 'Kos premium di pusat kota Surabaya. Sangat strategis, dekat stasiun Gubeng, pusat perbelanjaan dan perkantoran.',
            'gender_type' => 'putri', 'latitude' => -7.2753, 'longitude' => 112.7449,
            'images' => ['https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800', 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800'],
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60113',
            'name' => 'Kos Mulyosari Premium',
            'address' => 'Jl. Mulyosari Utara No. 88, Mulyosari',
            'description' => 'Kos asri di kawasan Mulyosari. Dekat ITS, PENS, dan kampus Unair. Cocok untuk mahasiswa dan karyawan.',
            'gender_type' => 'putra', 'latitude' => -7.2800, 'longitude' => 112.7950,
            'images' => ['https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800', 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800'],
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60236',
            'name' => 'Kos Petra Executive',
            'address' => 'Jl. Siwalankerto Timur No. 22, Wonocolo',
            'description' => 'Kos eksklusif dekat Universitas Kristen Petra. Cocok untuk mahasiswa yang menginginkan hunian bersih dan fasilitas lengkap.',
            'gender_type' => 'putri', 'latitude' => -7.3285, 'longitude' => 112.7225,
            'images' => ['https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800', 'https://images.unsplash.com/photo-1505693314120-0d443867891c?w=800'],
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60117',
            'name' => 'Kos ITS Mitra',
            'address' => 'Jl. Arief Rachman Hakim No. 45, Sukolilo',
            'description' => 'Kos strategis tepat di depan ITS Surabaya. Tersedia koneksi WiFi fiber optik dan akses 24 jam.',
            'gender_type' => 'putra', 'latitude' => -7.2817, 'longitude' => 112.7953,
            'images' => ['https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800', 'https://images.unsplash.com/photo-1571508601891-ca5e7a713859?w=800'],
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60119',
            'name' => 'Kos Menur Pumpungan',
            'address' => 'Jl. Menur Pumpungan No. 15, Sukolilo',
            'description' => 'Kos modern dekat RS Universitas Airlangga dan RSUD dr. Soetomo. Cocok untuk co-ass dan dokter muda.',
            'gender_type' => 'campur', 'latitude' => -7.2791, 'longitude' => 112.7567,
            'images' => ['https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800'],
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60226',
            'name' => 'Kos Darmo Permai',
            'address' => 'Jl. Darmo Permai III No. 7, Dukuh Pakis',
            'description' => 'Kos mewah di kawasan elite Darmo. Dekat pusat bisnis Surabaya Barat, WTC, dan Galaxy Mall.',
            'gender_type' => 'putri', 'latitude' => -7.2957, 'longitude' => 112.7127,
            'images' => ['https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800'],
        ],
        [
            'city' => 'Surabaya', 'province' => 'Jawa Timur', 'postal_code' => '60115',
            'name' => 'Kos Manyar Permai',
            'address' => 'Jl. Manyar Kertoadi No. 11, Mulyorejo',
            'description' => 'Hunian asri dan quiet zone di Manyar. Dekat UNAIR kampus C dan akses mudah ke pusat kota.',
            'gender_type' => 'campur', 'latitude' => -7.2702, 'longitude' => 112.7750,
            'images' => ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800'],
        ],
        // ─── JAKARTA (4 houses) ───────────────────────────────────────────
        [
            'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'postal_code' => '12110',
            'name' => 'Kos Eksklusif Senopati',
            'address' => 'Jl. Senopati No. 81, Kebayoran Baru',
            'description' => 'Kos premium dengan desain modern minimalis di kawasan elite Senopati. Dekat pusat bisnis SCBD dan Sudirman.',
            'gender_type' => 'putri', 'latitude' => -6.2297, 'longitude' => 106.8097,
            'images' => ['https://images.unsplash.com/photo-1505693314120-0d443867891c?w=800'],
        ],
        [
            'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'postal_code' => '12730',
            'name' => 'Kos Kemang Executive',
            'address' => 'Jl. Kemang Raya No. 12, Kemang',
            'description' => 'Kos bergaya di kawasan Kemang yang artsy dan cosmopolitan. Dekat restoran, cafe, dan pusat hiburan premium.',
            'gender_type' => 'campur', 'latitude' => -6.2615, 'longitude' => 106.8143,
            'images' => ['https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800'],
        ],
        [
            'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'postal_code' => '12820',
            'name' => 'Kos Nyaman Tebet',
            'address' => 'Jl. Tebet Timur Dalam No. 55, Tebet',
            'description' => 'Kos bersih dan nyaman di Tebet. Dekat taman Tebet Eco Park, stasiun MRT Tebet, dan berbagai kuliner.',
            'gender_type' => 'putri', 'latitude' => -6.2384, 'longitude' => 106.8459,
            'images' => ['https://images.unsplash.com/photo-1571508601891-ca5e7a713859?w=800'],
        ],
        [
            'city' => 'Jakarta Pusat', 'province' => 'DKI Jakarta', 'postal_code' => '10350',
            'name' => 'Kos Menteng Asri',
            'address' => 'Jl. Gondangdia Lama No. 30, Menteng',
            'description' => 'Kos heritage di kawasan Menteng yang bersejarah. Lingkungan tenang dengan bangunan klasik yang terawat.',
            'gender_type' => 'campur', 'latitude' => -6.1980, 'longitude' => 106.8384,
            'images' => ['https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800'],
        ],
        // ─── BANDUNG (2 houses) ───────────────────────────────────────────
        [
            'city' => 'Bandung', 'province' => 'Jawa Barat', 'postal_code' => '40135',
            'name' => 'Studio Apartment Dago',
            'address' => 'Jl. Dago (Ir. H. Juanda) No. 150, Coblong',
            'description' => 'Studio modern di jantung kota Bandung. View gunung dan udara sejuk khas Bandung. Dekat ITB dan Dago Entertainment.',
            'gender_type' => 'campur', 'latitude' => -6.8726, 'longitude' => 107.6101,
            'images' => ['https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800'],
        ],
        [
            'city' => 'Bandung', 'province' => 'Jawa Barat', 'postal_code' => '40161',
            'name' => 'Kos Minimalis Pasteur',
            'address' => 'Jl. Pasteur No. 22, Sukajadi',
            'description' => 'Kos modern dekat pintu tol Pasteur. Akses mudah ke seluruh Bandung dan cocok untuk karyawan.',
            'gender_type' => 'putra', 'latitude' => -6.8870, 'longitude' => 107.5876,
            'images' => ['https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800'],
        ],
        // ─── YOGYAKARTA (2 houses) ─────────────────────────────────────────
        [
            'city' => 'Sleman, Yogyakarta', 'province' => 'DI Yogyakarta', 'postal_code' => '55281',
            'name' => 'Kos Minimalis Seturan',
            'address' => 'Jl. Seturan Raya No. 56, Depok',
            'description' => 'Kos modern di kawasan Seturan Yogyakarta. Dekat UPN, AMIKOM, dan UGM. Harga terjangkau dengan fasilitas lengkap.',
            'gender_type' => 'putra', 'latitude' => -7.7674, 'longitude' => 110.3970,
            'images' => ['https://images.unsplash.com/photo-1531835551805-16d864c8d311?w=800'],
        ],
        [
            'city' => 'Sleman, Yogyakarta', 'province' => 'DI Yogyakarta', 'postal_code' => '55283',
            'name' => 'Kos Condongcatur Permai',
            'address' => 'Jl. Condongcatur No. 8, Depok',
            'description' => 'Kos nyaman di Condongcatur. Lingkungan mahasiswa yang ramai, akses mudah ke berbagai kampus di DIY.',
            'gender_type' => 'putri', 'latitude' => -7.7493, 'longitude' => 110.3899,
            'images' => ['https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800'],
        ],
    ];

    private array $roomTemplates = [
        ['type_name' => 'Kamar Standar', 'price_per_month' => 1200000, 'stock' => 10, 'size' => '3x3 m'],
        ['type_name' => 'Kamar Deluxe',  'price_per_month' => 1800000, 'stock' => 8, 'size' => '3x4 m'],
        ['type_name' => 'Kamar VIP',     'price_per_month' => 2500000, 'stock' => 5, 'size' => '4x4 m'],
    ];

    private array $indonesianNames = [
        'Budi Santoso', 'Siti Rahayu', 'Ahmad Fauzi', 'Dewi Lestari', 'Eko Prasetyo',
        'Fitri Handayani', 'Gunawan Wibowo', 'Hani Puspita', 'Irfan Maulana', 'Joko Susilo',
        'Kartika Sari', 'Lestari Ningrum', 'Muhammad Rizki', 'Nurul Azizah', 'Putri Maharani',
        'Reza Pratama', 'Sri Wahyuni', 'Teguh Santosa', 'Ulfa Ramadhani', 'Wahyu Hidayat',
    ];

    public function run(): void
    {
        // Pre-load facilities split by type for accurate seeding
        $sharedFacilities = Facility::where('type', FacilityType::BERSAMA->value)->get();
        $roomFacilities   = Facility::where('type', FacilityType::RUANG->value)->get();
        $allRules         = Rule::all();
        $faker            = \Faker\Factory::create('id_ID');

        // Create 10 tenant users for reviews
        $tenants = collect($this->indonesianNames)->map(function ($name) {
            return User::create([
                'name'         => $name,
                'email'        => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'password'     => bcrypt('password'),
                'phone_number' => '08' . rand(100000000, 999999999),
                'role'         => UserRole::TENANT->value,
                'is_verified'  => true,
            ]);
        });

        $this->command->info('✓ Tenants seeded: ' . $tenants->count());

        foreach ($this->housesData as $index => $data) {
            // Create owner
            $owner = User::create([
                'name'         => 'Owner ' . $data['name'],
                'email'        => 'owner.' . $index . '@kosku.id',
                'password'     => bcrypt('password'),
                'phone_number' => '0812' . rand(10000000, 99999999),
                'role'         => UserRole::OWNER->value,
                'is_verified'  => true,
            ]);

            // Create boarding house
            $house = BoardingHouse::create([
                'owner_id'     => $owner->id,
                'name'         => $data['name'],
                'description'  => $data['description'],
                'address'      => $data['address'],
                'city'         => $data['city'],
                'province'     => $data['province'],
                'postal_code'  => $data['postal_code'],
                'latitude'     => $data['latitude'],
                'longitude'    => $data['longitude'],
                'gender_type'  => $data['gender_type'],
            ]);

            // Attach 3–5 shared/area facilities to the boarding house
            $houseSharedFacilities = $sharedFacilities->count() > 0
                ? $sharedFacilities->random(min(5, $sharedFacilities->count()))->pluck('id')->toArray()
                : [];
            if ($houseSharedFacilities) {
                $house->facilities()->attach($houseSharedFacilities);
            }

            // Attach 3–5 rules from the master rules table
            if ($allRules->count() > 0) {
                $selectedRuleIds = $allRules->random(min(rand(3, 6), $allRules->count()))->pluck('id')->toArray();
                $house->rules()->attach($selectedRuleIds);
            }

            // Create 2–3 room types per house
            $imageUrls = $data['images'];
            $roomTemplatesForHouse = array_slice($this->roomTemplates, 0, rand(2, 3));

            $priceMultiplier = match (true) {
                in_array($data['city'], ['Jakarta Selatan', 'Jakarta Pusat']) => 1.8,
                $data['city'] === 'Bandung'                                   => 1.3,
                default => 1.0,
            };

            foreach ($roomTemplatesForHouse as $tpl) {
                $room = Room::create([
                    'boarding_house_id' => $house->id,
                    'type_name'         => $tpl['type_name'],
                    'price_per_month'   => (int) ($tpl['price_per_month'] * $priceMultiplier),
                    'stock'             => $tpl['stock'],
                    'size'              => $tpl['size'],
                    'image_url'         => $imageUrls[array_rand($imageUrls)],
                ]);

                // Attach 3–5 in-room facilities to each room
                $roomFacilityIds = $roomFacilities->count() > 0
                    ? $roomFacilities->random(min(5, $roomFacilities->count()))->pluck('id')->toArray()
                    : [];
                if ($roomFacilityIds) {
                    $room->facilities()->attach($roomFacilityIds);
                }
            }

            // Create 3–5 reviews per boarding house using contracts chain
            $reviewCount = rand(3, 5);
            $shuffledTenants = $tenants->shuffle()->take($reviewCount);
            $roomsForHouse   = $house->rooms;

            foreach ($shuffledTenants as $tenant) {
                $room = $roomsForHouse->random();

                $startDate = now()->subMonths(rand(3, 18))->toDateString();
                $endDate   = now()->subMonths(rand(0, 2))->toDateString();

                // Contract
                $contract = Contract::create([
                    'tenant_id'           => $tenant->id,
                    'room_id'             => $room->id,
                    'contract_number'     => '#KOS-' . now()->year . '-' . strtoupper(Str::random(8)),
                    'start_date'          => $startDate,
                    'end_date'            => $endDate,
                    'monthly_fee'         => $room->price_per_month,
                    'deposit_fee'         => $room->price_per_month,
                    'tenant_signature_date' => now()->subMonths(rand(3, 18)),
                    'owner_signature_date'  => now()->subMonths(rand(3, 18)),
                    'pdf_url'             => null,
                    'status'              => ContractStatus::EXPIRED->value,
                ]);

                // Review
                Review::create([
                    'contract_id'       => $contract->id,
                    'tenant_id'         => $tenant->id,
                    'boarding_house_id' => $house->id,
                    'rating'            => rand(3, 5),
                    'comment'           => $this->getRandomComment(),
                ]);
            }

            $this->command->info("✓ Seeded: {$data['name']} ({$data['city']})");
        }

        $this->command->info('');
        $this->command->info('✅ All boarding houses, rooms, facilities, and reviews seeded successfully!');
        $this->command->info('   Total boarding houses: ' . count($this->housesData));
        $this->command->info('   Total tenants: ' . $tenants->count());
    }

    private function getRandomComment(): string
    {
        $comments = [
            'Kos sangat bersih dan pemilik sangat ramah. Sangat rekomendasikan untuk yang baru pindah ke kota ini.',
            'Lokasi strategis, dekat dengan kampus dan fasilitas umum. AC dingin, WiFi kencang. Puas banget!',
            'Kamar luas dan terang, ventilasi bagus. Harga sepadan dengan fasilitas yang diberikan.',
            'Lingkungan tenang dan aman. Cocok untuk mahasiswa yang butuh konsentrasi belajar.',
            'Pelayanan pemilik sangat responsif. Masalah langsung ditangani. Recommended!',
            'Kamar mandi bersih, air panas tersedia. Parkir luas. Overall sangat worth it.',
            'Sudah 2 tahun di sini, betah banget. Teman-teman penghuni juga baik-baik.',
            'Tempatnya nyaman seperti di rumah sendiri. Harganya juga reasonable.',
            'Dekat dengan minimarket dan warung makan. Akses transportasi mudah.',
            'Bangunannya baru, desain modern. Sangat instagramable untuk WFH/study dari kamar.',
        ];
        return $comments[array_rand($comments)];
    }
}
