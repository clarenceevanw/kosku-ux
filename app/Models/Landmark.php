<?php

namespace App\Models;

use App\Enum\LandmarkType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Landmark extends Model
{
    use HasUuids;

    protected $fillable = [
        'district_id',
        'name',
        'type',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'type'      => LandmarkType::class,
            'latitude'  => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    // ── Helpers ───────────────────────────────────────────────────

    /**
     * Calculate the great-circle distance (km) between this landmark
     * and a given coordinate using the Haversine formula.
     */
    public function distanceTo(float $lat, float $lng): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat - (float) $this->latitude);
        $dLng = deg2rad($lng - (float) $this->longitude);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad((float) $this->latitude))
           * cos(deg2rad($lat))
           * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * asin(sqrt($a));
    }
}
