<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Predefined realistic Indonesian kos facilities.
     * icon value = Material Symbols Outlined ligature name.
     */
    private static array $predefinedFacilities = [
        ['name' => 'AC',             'icon' => 'ac_unit'],
        ['name' => 'WiFi Kencang',   'icon' => 'wifi'],
        ['name' => 'KM Dalam',       'icon' => 'shower'],
        ['name' => 'Kasur',          'icon' => 'bed'],
        ['name' => 'Lemari',         'icon' => 'checkroom'],
        ['name' => 'Meja Belajar',   'icon' => 'desk'],
        ['name' => 'Parkir Motor',   'icon' => 'two_wheeler'],
        ['name' => 'Parkir Mobil',   'icon' => 'directions_car'],
        ['name' => 'CCTV',           'icon' => 'security'],
        ['name' => 'Dapur Bersama',  'icon' => 'kitchen'],
        ['name' => 'Laundry',        'icon' => 'local_laundry_service'],
        ['name' => 'Smart TV',       'icon' => 'tv'],
        ['name' => 'Kulkas',         'icon' => 'kitchen'],
        ['name' => 'Dispenser',      'icon' => 'water_drop'],
        ['name' => 'Mushola',        'icon' => 'mosque'],
        ['name' => 'Ruang Tamu',     'icon' => 'living'],
        ['name' => 'Balkon',         'icon' => 'deck'],
        ['name' => 'Jemur Baju',     'icon' => 'dry'],
        ['name' => 'Air Panas',      'icon' => 'water_heater'],
        ['name' => 'Meja Makan',     'icon' => 'dining'],
    ];

    private static int $currentIndex = 0;

    public function definition(): array
    {
        $facility = static::$predefinedFacilities[static::$currentIndex % count(static::$predefinedFacilities)];
        static::$currentIndex++;

        return [
            'name' => $facility['name'],
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
}
