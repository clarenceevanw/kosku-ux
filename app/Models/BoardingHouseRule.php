<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;

/**
 * BoardingHouseRule — pivot between boarding_houses and rules (master table).
 *
 * Extends Pivot (not Model) to participate properly in BelongsToMany relationships.
 */
#[Fillable(['boarding_house_id', 'rule_id'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
class BoardingHouseRule extends Pivot
{
    use HasUuids;

    protected $table = 'boarding_house_rules';

    public $incrementing = false;

    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }
}
