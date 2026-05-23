<?php

namespace App\Models;

use App\Enum\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['tenant_id', 'room_id', 'contract_id', 'start_date', 'end_date', 'billing_month', 'due_date', 'total_amount', 'payment_status', 'payment_method', 'paid_at'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
#[Hidden([])]
class Transaction extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'payment_status' => PaymentStatus::class,
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

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }
}
