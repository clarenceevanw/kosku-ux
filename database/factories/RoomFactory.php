<?php

namespace Database\Factories;

use App\Models\BoardingHouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    private static array $roomTypes = [
        ['type_name' => 'Kamar Standar', 'price_range' => [800000, 1500000],  'size' => '3x3 m', 'stock_range' => [3, 8]],
        ['type_name' => 'Kamar Deluxe',  'price_range' => [1500001, 2500000], 'size' => '3x4 m', 'stock_range' => [2, 5]],
        ['type_name' => 'Kamar VIP',     'price_range' => [2500001, 4000000], 'size' => '4x4 m', 'stock_range' => [1, 3]],
        ['type_name' => 'Studio',        'price_range' => [3000000, 6000000], 'size' => '5x6 m', 'stock_range' => [1, 3]],
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement(static::$roomTypes);
        $images = [
            'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800',
            'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=800',
            'https://images.unsplash.com/photo-1571508601891-ca5e7a713859?w=800',
            'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800',
            'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800',
        ];
        return [
            'boarding_house_id' => BoardingHouse::factory(),
            'type_name'         => $type['type_name'],
            'price_per_month'   => $this->faker->numberBetween(...$type['price_range']),
            'stock'             => $this->faker->numberBetween(...$type['stock_range']),
            'size'              => $type['size'],
            'image_url'         => $this->faker->randomElement($images),
        ];
    }

    public function standard(): static
    {
        return $this->state(fn ($a) => [
            'type_name'       => 'Kamar Standar',
            'price_per_month' => $this->faker->numberBetween(800000, 1500000),
            'size'            => '3x3 m',
            'stock'           => $this->faker->numberBetween(3, 8),
        ]);
    }
}
