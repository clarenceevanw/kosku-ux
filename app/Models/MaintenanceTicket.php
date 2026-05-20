<?php

namespace App\Models;

use App\Enum\PriorityLevel;
use App\Enum\TicketStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['tenant_id', 'room_id', 'title', 'description', 'photo_url', 'priority', 'status'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
#[Hidden([])]
class MaintenanceTicket extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'priority' => PriorityLevel::class,
            'status' => TicketStatus::class,
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
