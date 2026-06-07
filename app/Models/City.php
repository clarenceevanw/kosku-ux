<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    /**
     * BPS 4-char string primary key (e.g. "3578" = Kota Surabaya).
     */
    public $incrementing = false;
    public $timestamps   = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'province_id', 'name'];

    // ── Relationships ──────────────────────────────────────────────

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
