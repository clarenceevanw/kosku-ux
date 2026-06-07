<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    /**
     * BPS 7-char string primary key (e.g. "3578010" = Kec. Wonocolo, Surabaya).
     */
    public $incrementing = false;
    public $timestamps   = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'city_id', 'name'];

    // ── Relationships ──────────────────────────────────────────────

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function boardingHouses(): HasMany
    {
        return $this->hasMany(BoardingHouse::class);
    }

    public function landmarks(): HasMany
    {
        return $this->hasMany(Landmark::class);
    }
}
