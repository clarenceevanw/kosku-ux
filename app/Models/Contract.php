<?php

namespace App\Models;

use App\Enum\ContractStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['transaction_id', 'contract_number', 'start_date', 'end_date', 'monthly_fee', 'deposit_fee', 'tenant_signature_date', 'owner_signature_date', 'pdf_url', 'status'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
#[Hidden([])]
class Contract extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'tenant_signature_date' => 'datetime',
            'owner_signature_date' => 'datetime',
            'status' => ContractStatus::class,
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function monthlyPayments(): HasMany
    {
        return $this->hasMany(MonthlyPayment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
