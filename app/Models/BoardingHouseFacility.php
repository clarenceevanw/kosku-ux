<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Fillable(['boarding_house_id', 'facility_id'])]
#[Guarded(['id'])]
#[Hidden([])]
#[Table('boarding_house_facility')]
class BoardingHouseFacility extends Pivot
{
    use HasUuids;
}
