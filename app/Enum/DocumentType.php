<?php

namespace App\Enum;

enum DocumentType: string
{
    // Tenant documents
    case KTP = 'ktp';
    case KTM = 'ktm';

    // Owner documents
    case OWNER_KTP  = 'owner_ktp';
    case PBB        = 'pbb';
    case ELECTRICITY_BILL = 'electricity_bill';
    case WATER_BILL = 'water_bill';

    public function label(): string
    {
        return match ($this) {
            self::KTP              => 'KTP (Kartu Tanda Penduduk)',
            self::KTM              => 'KTM (Kartu Tanda Mahasiswa)',
            self::OWNER_KTP        => 'KTP Pemilik',
            self::PBB              => 'Tagihan PBB',
            self::ELECTRICITY_BILL => 'Tagihan Listrik',
            self::WATER_BILL       => 'Tagihan Air',
        };
    }

    public static function tenantTypes(): array
    {
        return [self::KTP, self::KTM];
    }

    public static function ownerTypes(): array
    {
        return [self::OWNER_KTP, self::PBB, self::ELECTRICITY_BILL, self::WATER_BILL];
    }
}
