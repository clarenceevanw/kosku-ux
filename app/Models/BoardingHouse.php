<?php

namespace App\Models;

use App\Enum\GenderType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['owner_id', 'name', 'description', 'address', 'city', 'province', 'postal_code', 'latitude', 'longitude', 'gender_type'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
#[Hidden([])]
class BoardingHouse extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'gender_type' => GenderType::class,
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(BoardingHouseRule::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class)
            ->using(BoardingHouseFacility::class)
            ->withPivot('id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
