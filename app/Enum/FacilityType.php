<?php

namespace App\Enum;

enum FacilityType: string
{
    /** Fasilitas di area umum / bersama kos (lobby, parkir, laundry, dll) */
    case BERSAMA = 'bersama';

    /** Fasilitas di dalam kamar (AC, kasur, kamar mandi dalam, dll) */
    case RUANG = 'ruang';

    public function label(): string
    {
        return match ($this) {
            self::BERSAMA => 'Fasilitas Bersama',
            self::RUANG   => 'Fasilitas Kamar',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::BERSAMA => 'Fasilitas di area umum kos (lobby, parkir, laundry, dll)',
            self::RUANG   => 'Fasilitas di dalam kamar (AC, kasur, kamar mandi, dll)',
        };
    }
}
