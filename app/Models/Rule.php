<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;

/**
 * Rule — master lookup table for standardised boarding-house rules.
 *
 * category: grouping label  e.g. "Tamu & Kunjungan", "Keamanan & Akses"
 * name:     concise rule    e.g. "Tamu dilarang menginap"
 * icon:     Material Symbols Outlined ligature name
 */
#[Fillable(['category', 'name', 'icon'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
class Rule extends Model
{
    use HasUuids;

    public function boardingHouses(): BelongsToMany
    {
        return $this->belongsToMany(BoardingHouse::class, 'boarding_house_rules')
                    ->using(BoardingHouseRule::class)
                    ->withPivot('id')
                    ->withTimestamps();
    }
}
