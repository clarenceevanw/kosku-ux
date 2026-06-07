<?php

namespace App\Enum;

enum LandmarkType: string
{
    case CAMPUS  = 'campus';
    case STATION = 'station';
    case MALL    = 'mall';

    public function label(): string
    {
        return match ($this) {
            self::CAMPUS  => 'Kampus',
            self::STATION => 'Stasiun',
            self::MALL    => 'Pusat Perbelanjaan',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::CAMPUS  => 'school',
            self::STATION => 'train',
            self::MALL    => 'shopping_bag',
        };
    }
}
