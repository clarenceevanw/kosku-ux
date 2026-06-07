<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    /**
     * BPS 2-char string primary key (e.g. "35" = Jawa Timur).
     * Not auto-incrementing — we manage the ID ourselves.
     */
    public $incrementing = false;
    public $timestamps   = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'name'];

    // ── Relationships ──────────────────────────────────────────────

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
