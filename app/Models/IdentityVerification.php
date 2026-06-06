<?php

namespace App\Models;

use App\Enum\DocumentType;
use App\Enum\VerificationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityVerification extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'document_type',
        'file_path',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => DocumentType::class,
            'status'        => VerificationStatus::class,
            'reviewed_at'   => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ─── Helpers ──────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === VerificationStatus::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === VerificationStatus::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === VerificationStatus::REJECTED;
    }
}
