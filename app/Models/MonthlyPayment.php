<?php

namespace App\Models;

use App\Enum\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['contract_id', 'billing_month', 'due_date', 'amount', 'payment_status', 'payment_method', 'paid_at'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
#[Hidden([])]
class MonthlyPayment extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'payment_status' => PaymentStatus::class,
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
