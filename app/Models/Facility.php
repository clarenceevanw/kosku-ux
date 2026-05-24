<?php

namespace App\Models;

use App\Enum\FacilityType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['name', 'type', 'icon'])]
#[Guarded(['id'])]
#[Hidden([])]
class Facility extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'type' => FacilityType::class,
        ];
    }

    public function boardingHouses(): BelongsToMany
    {
        return $this->belongsToMany(BoardingHouse::class)
            ->using(BoardingHouseFacility::class)
            ->withPivot('id');
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_facility')
            ->using(RoomFacility::class)
            ->withPivot('id');
    }
}
