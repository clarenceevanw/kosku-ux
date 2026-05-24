<?php

namespace Database\Factories;

use App\Enum\FacilityType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Predefined realistic Indonesian kos facilities.
     * type: 'bersama' = shared/common area facilities
     *       'ruang'   = in-room facilities
     * icon: Material Symbols Outlined ligature name.
     */
    private static array $predefinedFacilities = [
        // ── Fasilitas Bersama (shared area) ──────────────────────────────────
        ['name' => 'Parkir Motor',    'type' => FacilityType::BERSAMA, 'icon' => 'two_wheeler'],
        ['name' => 'Parkir Mobil',    'type' => FacilityType::BERSAMA, 'icon' => 'directions_car'],
        ['name' => 'Dapur Bersama',   'type' => FacilityType::BERSAMA, 'icon' => 'kitchen'],
        ['name' => 'Laundry',         'type' => FacilityType::BERSAMA, 'icon' => 'local_laundry_service'],
        ['name' => 'CCTV',            'type' => FacilityType::BERSAMA, 'icon' => 'security'],
        ['name' => 'Mushola',         'type' => FacilityType::BERSAMA, 'icon' => 'mosque'],
        ['name' => 'Ruang Tamu',      'type' => FacilityType::BERSAMA, 'icon' => 'living'],
        ['name' => 'Area Jemur',      'type' => FacilityType::BERSAMA, 'icon' => 'dry'],
        ['name' => 'Meja Makan',      'type' => FacilityType::BERSAMA, 'icon' => 'dining'],
        ['name' => 'WiFi Bersama',    'type' => FacilityType::BERSAMA, 'icon' => 'wifi'],
        ['name' => 'Dispenser',       'type' => FacilityType::BERSAMA, 'icon' => 'water_drop'],
        ['name' => 'Penjaga 24 Jam',  'type' => FacilityType::BERSAMA, 'icon' => 'person_pin_circle'],

        // ── Fasilitas Kamar (in-room) ─────────────────────────────────────────
        ['name' => 'AC',              'type' => FacilityType::RUANG, 'icon' => 'ac_unit'],
        ['name' => 'WiFi Kencang',    'type' => FacilityType::RUANG, 'icon' => 'wifi'],
        ['name' => 'KM Dalam',        'type' => FacilityType::RUANG, 'icon' => 'shower'],
        ['name' => 'Kasur',           'type' => FacilityType::RUANG, 'icon' => 'bed'],
        ['name' => 'Lemari',          'type' => FacilityType::RUANG, 'icon' => 'checkroom'],
        ['name' => 'Meja Belajar',    'type' => FacilityType::RUANG, 'icon' => 'desk'],
        ['name' => 'Smart TV',        'type' => FacilityType::RUANG, 'icon' => 'tv'],
        ['name' => 'Kulkas Mini',     'type' => FacilityType::RUANG, 'icon' => 'kitchen'],
        ['name' => 'Balkon',          'type' => FacilityType::RUANG, 'icon' => 'deck'],
        ['name' => 'Air Panas',       'type' => FacilityType::RUANG, 'icon' => 'water_heater'],
    ];

    private static int $currentIndex = 0;

    public function definition(): array
    {
        $facility = static::$predefinedFacilities[static::$currentIndex % count(static::$predefinedFacilities)];
        static::$currentIndex++;

        return [
            'name' => $facility['name'],
            'type' => $facility['type']->value,
            'icon' => $facility['icon'],
        ];
    }

    /** Get a random predefined facility definition without cycling */
    public static function randomPredefined(): array
    {
        return static::$predefinedFacilities[array_rand(static::$predefinedFacilities)];
    }

    /** Get all predefined facilities */
    public static function allPredefined(): array
    {
        return static::$predefinedFacilities;
    }

    /** Get all predefined facilities of a specific type */
    public static function predefinedByType(FacilityType $type): array
    {
        return array_filter(
            static::$predefinedFacilities,
            fn ($f) => $f['type'] === $type
        );
    }
}
