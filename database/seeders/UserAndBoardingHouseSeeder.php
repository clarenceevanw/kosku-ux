<?php

namespace Database\Seeders;

use App\Enum\ContractStatus;
use App\Enum\FacilityType;
use App\Enum\PaymentStatus;
use App\Enum\UserRole;
use App\Models\BoardingHouse;
use App\Models\Contract;
use App\Models\Facility;
use App\Models\MaintenanceTicket;
use App\Models\MonthlyPayment;
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
            'district_id' => '3578020', // Rungkut, Surabaya
            'postal_code' => '60293',
            'name' => 'Kos Rungkut Harmoni',
            'address' => 'Jl. Rungkut Mapan Utara No. 12, Rungkut',
            'description' => 'Kos nyaman di kawasan Rungkut, dekat dengan UBAYA dan kawasan industri SIER. Lingkungan tenang, keamanan 24 jam dengan CCTV.',
            'gender_type' => 'campur', 'latitude' => -7.3218, 'longitude' => 112.7560,
            'images' => ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800', 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800'],
        ],
        [
            'district_id' => '3578030', // Gubeng, Surabaya
            'postal_code' => '60282',
            'name' => 'Kos Gubeng Residence',
            'address' => 'Jl. Gubeng Kertajaya VIII No. 5, Gubeng',
            'description' => 'Kos premium di pusat kota Surabaya. Sangat strategis, dekat stasiun Gubeng, pusat perbelanjaan dan perkantoran.',
            'gender_type' => 'putri', 'latitude' => -7.2753, 'longitude' => 112.7449,
            'images' => ['https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800', 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800'],
        ],
        [
            'district_id' => '3578060', // Mulyorejo, Surabaya
            'postal_code' => '60113',
            'name' => 'Kos Mulyosari Premium',
            'address' => 'Jl. Mulyosari Utara No. 88, Mulyosari',
            'description' => 'Kos asri di kawasan Mulyosari. Dekat ITS, PENS, dan kampus Unair. Cocok untuk mahasiswa dan karyawan.',
            'gender_type' => 'putra', 'latitude' => -7.2800, 'longitude' => 112.7950,
            'images' => ['https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800', 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800'],
        ],
        [
            'district_id' => '3578010', // Wonocolo, Surabaya
            'postal_code' => '60236',
            'name' => 'Kos Petra Executive',
            'address' => 'Jl. Siwalankerto Timur No. 22, Wonocolo',
            'description' => 'Kos eksklusif dekat Universitas Kristen Petra. Cocok untuk mahasiswa yang menginginkan hunian bersih dan fasilitas lengkap.',
            'gender_type' => 'putri', 'latitude' => -7.3285, 'longitude' => 112.7225,
            'images' => ['https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800', 'https://images.unsplash.com/photo-1505693314120-0d443867891c?w=800'],
        ],
        [
            'district_id' => '3578050', // Sukolilo, Surabaya
            'postal_code' => '60117',
            'name' => 'Kos ITS Mitra',
            'address' => 'Jl. Arief Rachman Hakim No. 45, Sukolilo',
            'description' => 'Kos strategis tepat di depan ITS Surabaya. Tersedia koneksi WiFi fiber optik dan akses 24 jam.',
            'gender_type' => 'putra', 'latitude' => -7.2817, 'longitude' => 112.7953,
            'images' => ['https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800', 'https://images.unsplash.com/photo-1571508601891-ca5e7a713859?w=800'],
        ],
        [
            'district_id' => '3578050', // Sukolilo, Surabaya
            'postal_code' => '60119',
            'name' => 'Kos Menur Pumpungan',
            'address' => 'Jl. Menur Pumpungan No. 15, Sukolilo',
            'description' => 'Kos modern dekat RS Universitas Airlangga dan RSUD dr. Soetomo. Cocok untuk co-ass dan dokter muda.',
            'gender_type' => 'campur', 'latitude' => -7.2791, 'longitude' => 112.7567,
            'images' => ['https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800'],
        ],
        [
            'district_id' => '3578040', // Dukuh Pakis, Surabaya
            'postal_code' => '60226',
            'name' => 'Kos Darmo Permai',
            'address' => 'Jl. Darmo Permai III No. 7, Dukuh Pakis',
            'description' => 'Kos mewah di kawasan elite Darmo. Dekat pusat bisnis Surabaya Barat, WTC, dan Galaxy Mall.',
            'gender_type' => 'putri', 'latitude' => -7.2957, 'longitude' => 112.7127,
            'images' => ['https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800'],
        ],
        [
            'district_id' => '3578060', // Mulyorejo, Surabaya
            'postal_code' => '60115',
            'name' => 'Kos Manyar Permai',
            'address' => 'Jl. Manyar Kertoadi No. 11, Mulyorejo',
            'description' => 'Hunian asri dan quiet zone di Manyar. Dekat UNAIR kampus C dan akses mudah ke pusat kota.',
            'gender_type' => 'campur', 'latitude' => -7.2702, 'longitude' => 112.7750,
            'images' => ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800'],
        ],
        // ─── JAKARTA (4 houses) ───────────────────────────────────────────
        [
            'district_id' => '3171010', // Kebayoran Baru, Jakarta Selatan
            'postal_code' => '12110',
            'name' => 'Kos Eksklusif Senopati',
            'address' => 'Jl. Senopati No. 81, Kebayoran Baru',
            'description' => 'Kos premium dengan desain modern minimalis di kawasan elite Senopati. Dekat pusat bisnis SCBD dan Sudirman.',
            'gender_type' => 'putri', 'latitude' => -6.2297, 'longitude' => 106.8097,
            'images' => ['https://images.unsplash.com/photo-1505693314120-0d443867891c?w=800'],
        ],
        [
            'district_id' => '3171020', // Kemang, Jakarta Selatan
            'postal_code' => '12730',
            'name' => 'Kos Kemang Executive',
            'address' => 'Jl. Kemang Raya No. 12, Kemang',
            'description' => 'Kos bergaya di kawasan Kemang yang artsy dan cosmopolitan. Dekat restoran, cafe, dan pusat hiburan premium.',
            'gender_type' => 'campur', 'latitude' => -6.2615, 'longitude' => 106.8143,
            'images' => ['https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800'],
        ],
        [
            'district_id' => '3171030', // Tebet, Jakarta Selatan
            'postal_code' => '12820',
            'name' => 'Kos Nyaman Tebet',
            'address' => 'Jl. Tebet Timur Dalam No. 55, Tebet',
            'description' => 'Kos bersih dan nyaman di Tebet. Dekat taman Tebet Eco Park, stasiun MRT Tebet, dan berbagai kuliner.',
            'gender_type' => 'putri', 'latitude' => -6.2384, 'longitude' => 106.8459,
            'images' => ['https://images.unsplash.com/photo-1571508601891-ca5e7a713859?w=800'],
        ],
        [
            'district_id' => '3173010', // Menteng, Jakarta Pusat
            'postal_code' => '10350',
            'name' => 'Kos Menteng Asri',
            'address' => 'Jl. Gondangdia Lama No. 30, Menteng',
            'description' => 'Kos heritage di kawasan Menteng yang bersejarah. Lingkungan tenang dengan bangunan klasik yang terawat.',
            'gender_type' => 'campur', 'latitude' => -6.1980, 'longitude' => 106.8384,
            'images' => ['https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800'],
        ],
        // ─── BANDUNG (2 houses) ───────────────────────────────────────────
        [
            'district_id' => '3273010', // Coblong, Bandung
            'postal_code' => '40135',
            'name' => 'Studio Apartment Dago',
            'address' => 'Jl. Dago (Ir. H. Juanda) No. 150, Coblong',
            'description' => 'Studio modern di jantung kota Bandung. View gunung dan udara sejuk khas Bandung. Dekat ITB dan Dago Entertainment.',
            'gender_type' => 'campur', 'latitude' => -6.8726, 'longitude' => 107.6101,
            'images' => ['https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800'],
        ],
        [
            'district_id' => '3273020', // Sukajadi, Bandung
            'postal_code' => '40161',
            'name' => 'Kos Minimalis Pasteur',
            'address' => 'Jl. Pasteur No. 22, Sukajadi',
            'description' => 'Kos modern dekat pintu tol Pasteur. Akses mudah ke seluruh Bandung dan cocok untuk karyawan.',
            'gender_type' => 'putra', 'latitude' => -6.8870, 'longitude' => 107.5876,
            'images' => ['https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800'],
        ],
        // ─── YOGYAKARTA (2 houses) ─────────────────────────────────────────
        [
            'district_id' => '3404010', // Depok, Sleman
            'postal_code' => '55281',
            'name' => 'Kos Minimalis Seturan',
            'address' => 'Jl. Seturan Raya No. 56, Depok',
            'description' => 'Kos modern di kawasan Seturan Yogyakarta. Dekat UPN, AMIKOM, dan UGM. Harga terjangkau dengan fasilitas lengkap.',
            'gender_type' => 'putra', 'latitude' => -7.7674, 'longitude' => 110.3970,
            'images' => ['https://images.unsplash.com/photo-1531835551805-16d864c8d311?w=800'],
        ],
        [
            'district_id' => '3404010', // Depok, Sleman
            'postal_code' => '55283',
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
                'owner_id'    => $owner->id,
                'name'        => $data['name'],
                'description' => $data['description'],
                'address'     => $data['address'],
                'district_id' => $data['district_id'],
                'postal_code' => $data['postal_code'],
                'latitude'    => $data['latitude'],
                'longitude'   => $data['longitude'],
                'gender_type' => $data['gender_type'],
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
                in_array($data['district_id'], ['3171010', '3171020', '3171030', '3173010']) => 1.8, // Jakarta
                in_array($data['district_id'], ['3273010', '3273020'])                       => 1.3, // Bandung
                default                                                                       => 1.0,
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

            $this->command->info("✓ Seeded: {$data['name']} (district: {$data['district_id']})");
        }

        $this->seedUsabilityDemoScenario($tenants, $sharedFacilities, $roomFacilities, $allRules);

        $this->command->info('');
        $this->command->info('✅ All boarding houses, rooms, facilities, and reviews seeded successfully!');
        $this->command->info('   Total boarding houses: ' . count($this->housesData));
        $this->command->info('   Total tenants: ' . $tenants->count());
    }

    private function seedUsabilityDemoScenario($tenants, $sharedFacilities, $roomFacilities, $allRules): void
    {
        $owner = User::updateOrCreate(
            ['email' => 'budi@kosku.id'],
            [
                'name'         => 'Pak Budi',
                'password'     => bcrypt('password'),
                'phone_number' => '081299990001',
                'role'         => UserRole::OWNER->value,
                'is_verified'  => true,
            ]
        );

        $aldi = User::updateOrCreate(
            ['email' => 'aldi@kosku.id'],
            [
                'name'         => 'Aldi',
                'password'     => bcrypt('password'),
                'phone_number' => '081200000001',
                'role'         => UserRole::TENANT->value,
                'is_verified'  => true,
            ]
        );

        $jessica = User::updateOrCreate(
            ['email' => 'jessica@kosku.id'],
            [
                'name'         => 'Jessica',
                'password'     => bcrypt('password'),
                'phone_number' => '081200000002',
                'role'         => UserRole::TENANT->value,
                'is_verified'  => true,
            ]
        );

        $house = BoardingHouse::updateOrCreate(
            [
                'owner_id' => $owner->id,
                'name' => 'Kos Petra Residence',
            ],
            [
                'description' => 'Kos dekat Universitas Kristen Petra dengan akses mudah ke kampus, minimarket, dan jalan utama Siwalankerto. Cocok untuk skenario booking, penghuni aktif, dan pengelolaan owner.',
                'address'     => 'Jl. Siwalankerto Timur No. 18, Wonocolo',
                'district_id' => '3578010',
                'postal_code' => '60236',
                'latitude'    => -7.3292,
                'longitude'   => 112.7237,
                'gender_type' => 'campur',
            ]
        );

        $house->facilities()->sync($this->facilityIdsByName($sharedFacilities, [
            'CCTV',
            'Parkir Motor',
            'Dapur Bersama',
            'WiFi Bersama',
        ]));

        if ($allRules->isNotEmpty()) {
            $house->rules()->sync($allRules->take(4)->pluck('id')->toArray());
        }

        $roomSpecs = [
            [
                'type_name' => 'Kamar Standard',
                'price_per_month' => 1250000,
                'stock' => 4,
                'size' => '3x3 m',
                'image_url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800',
            ],
            [
                'type_name' => 'Kamar Deluxe',
                'price_per_month' => 1450000,
                'stock' => 3,
                'size' => '3x4 m',
                'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800',
            ],
            [
                'type_name' => 'Kamar Premium',
                'price_per_month' => 1650000,
                'stock' => 2,
                'size' => '4x4 m',
                'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
            ],
        ];

        $rooms = collect($roomSpecs)->map(function (array $spec) use ($house, $roomFacilities) {
            $room = Room::updateOrCreate(
                [
                    'boarding_house_id' => $house->id,
                    'type_name' => $spec['type_name'],
                ],
                [
                    'price_per_month' => $spec['price_per_month'],
                    'stock'           => $spec['stock'],
                    'size'            => $spec['size'],
                    'image_url'       => $spec['image_url'],
                ]
            );

            $room->facilities()->sync($this->facilityIdsByName($roomFacilities, [
                'AC',
                'WiFi Kencang',
                'Kasur',
                'Lemari',
            ]));

            return $room;
        });

        $activeRoom = $rooms->firstWhere('type_name', 'Kamar Standard');
        $activeContract = Contract::updateOrCreate(
            [
                'tenant_id' => $jessica->id,
                'room_id'   => $activeRoom->id,
            ],
            [
                'contract_number'       => 'KOS-2026-PTR-0001',
                'start_date'            => now()->subMonth()->startOfMonth(),
                'end_date'              => now()->addMonth()->endOfMonth(),
                'monthly_fee'           => $activeRoom->price_per_month,
                'deposit_fee'           => $activeRoom->price_per_month,
                'tenant_signature_date' => now()->subMonth()->subDays(2),
                'owner_signature_date'  => now()->subMonth()->subDay(),
                'pdf_url'               => null,
                'status'                => ContractStatus::ACTIVE->value,
            ]
        );

        $firstBillingDueDate = now()->subMonth()->startOfMonth()->addDays(4);
        $secondBillingDueDate = now()->startOfMonth()->addDays(4);
        $thirdBillingDueDate = now()->addMonth()->startOfMonth()->addDays(4);

        $this->upsertMonthlyPayments($activeContract, [
            [
                'billing_month'   => 1,
                'due_date'        => $firstBillingDueDate->toDateString(),
                'amount'          => $activeRoom->price_per_month,
                'payment_status'  => PaymentStatus::RELEASED_TO_OWNER->value,
                'payment_method'  => 'Virtual Account',
                'paid_at'         => $firstBillingDueDate->copy()->addDay(),
            ],
            [
                'billing_month'   => 2,
                'due_date'        => $secondBillingDueDate->toDateString(),
                'amount'          => $activeRoom->price_per_month,
                'payment_status'  => PaymentStatus::PENDING->value,
                'payment_method'  => null,
                'paid_at'         => null,
            ],
            [
                'billing_month'   => 3,
                'due_date'        => $thirdBillingDueDate->toDateString(),
                'amount'          => $activeRoom->price_per_month,
                'payment_status'  => PaymentStatus::PENDING->value,
                'payment_method'  => null,
                'paid_at'         => null,
            ],
        ]);

        Review::updateOrCreate(
            ['contract_id' => $activeContract->id],
            [
                'tenant_id'         => $jessica->id,
                'boarding_house_id'  => $house->id,
                'rating'             => 5,
                'comment'            => 'Kosnya bersih, aman, dan pemilik cepat tanggap. Cocok untuk tinggal jangka menengah.',
            ]
        );

        $searchableRooms = [
            [
                'name' => 'Kos Siwalankerto Harmony',
                'district_id' => '3578010',
                'postal_code' => '60236',
                'description' => 'Kos putra dekat Petra dan kampus sekitar Wonocolo. Filter manual akan menemukan listing ini dengan AC, WiFi, dan budget di bawah Rp 1,5 juta.',
                'gender_type' => 'putra',
                'price' => 1100000,
                'room_name' => 'Kamar Reguler',
                'stock' => 5,
            ],
            [
                'name' => 'Kos Petra Guesthouse',
                'district_id' => '3578010',
                'postal_code' => '60236',
                'description' => 'Kos putri dekat Universitas Kristen Petra dengan fasilitas kamar AC dan WiFi yang lengkap.',
                'gender_type' => 'putri',
                'price' => 1350000,
                'room_name' => 'Kamar Superior',
                'stock' => 4,
            ],
            [
                'name' => 'Kos Wonocolo Budget',
                'district_id' => '3578010',
                'postal_code' => '60237',
                'description' => 'Kos putra ekonomis di sekitar Siwalankerto untuk membandingkan hasil filter harga manual.',
                'gender_type' => 'putra',
                'price' => 950000,
                'room_name' => 'Kamar Ekonomis',
                'stock' => 6,
            ],
        ];

        foreach ($searchableRooms as $index => $data) {
            $otherOwner = User::updateOrCreate(
                ['email' => 'owner.usability.' . ($index + 1) . '@kosku.id'],
                [
                    'name'         => 'Owner Usability ' . ($index + 1),
                    'password'     => bcrypt('password'),
                    'phone_number' => '0812333300' . ($index + 1),
                    'role'         => UserRole::OWNER->value,
                    'is_verified'  => true,
                ]
            );

            $demoHouse = BoardingHouse::updateOrCreate(
                [
                    'owner_id' => $otherOwner->id,
                    'name' => $data['name'],
                ],
                [
                    'description' => $data['description'],
                    'address'     => 'Jl. Siwalankerto No. ' . (20 + $index) . ', Wonocolo',
                    'district_id' => $data['district_id'],
                    'postal_code' => $data['postal_code'],
                    'latitude'    => -7.3295 + ($index * 0.0012),
                    'longitude'   => 112.7241 + ($index * 0.0011),
                    'gender_type' => $data['gender_type'],
                ]
            );

            $demoHouse->facilities()->sync($this->facilityIdsByName($sharedFacilities, [
                'CCTV',
                'Parkir Motor',
                'Dapur Bersama',
                'WiFi Bersama',
            ]));

            if ($allRules->isNotEmpty()) {
                $demoHouse->rules()->sync($allRules->skip($index)->take(4)->pluck('id')->toArray());
            }

            $demoRoom = Room::updateOrCreate(
                [
                    'boarding_house_id' => $demoHouse->id,
                    'type_name' => $data['room_name'],
                ],
                [
                    'price_per_month' => $data['price'],
                    'stock'           => $data['stock'],
                    'size'            => '3x3 m',
                    'image_url'       => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800',
                ]
            );

            $demoRoom->facilities()->sync($this->facilityIdsByName($roomFacilities, [
                'AC',
                'WiFi Kencang',
                'Kasur',
                'Lemari',
            ]));

            $reviewTenant = $tenants->values()->get($index) ?? $tenants->first();

            $demoContract = Contract::updateOrCreate(
                [
                    'tenant_id' => $reviewTenant->id,
                    'room_id'   => $demoRoom->id,
                ],
                [
                    'contract_number'       => 'KOS-DEMO-REV-' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'start_date'            => now()->subMonths(4)->startOfMonth(),
                    'end_date'              => now()->subMonths(1)->endOfMonth(),
                    'monthly_fee'           => $demoRoom->price_per_month,
                    'deposit_fee'           => $demoRoom->price_per_month,
                    'tenant_signature_date' => now()->subMonths(4)->subDays(2),
                    'owner_signature_date'  => now()->subMonths(4)->subDay(),
                    'pdf_url'               => null,
                    'status'                => ContractStatus::EXPIRED->value,
                ]
            );

            Review::updateOrCreate(
                ['contract_id' => $demoContract->id],
                [
                    'tenant_id'         => $reviewTenant->id,
                    'boarding_house_id'  => $demoHouse->id,
                    'rating'             => 4 + ($index % 2),
                    'comment'            => $this->getRandomComment(),
                ]
            );
        }

        $this->command->info('✓ Usability demo seeded: Aldi, Jessica, and Pak Budi are linked to the same kos flow.');
    }

    private function facilityIdsByName($facilities, array $names): array
    {
        return $facilities
            ->whereIn('name', $names)
            ->pluck('id')
            ->values()
            ->toArray();
    }

    private function upsertMonthlyPayments(Contract $contract, array $payments): void
    {
        foreach ($payments as $payment) {
            MonthlyPayment::updateOrCreate(
                [
                    'contract_id'   => $contract->id,
                    'billing_month' => $payment['billing_month'],
                ],
                [
                    'due_date'       => $payment['due_date'],
                    'amount'         => $payment['amount'],
                    'payment_status' => $payment['payment_status'],
                    'payment_method' => $payment['payment_method'],
                    'paid_at'        => $payment['paid_at'],
                ]
            );
        }
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
