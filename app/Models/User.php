<?php

namespace App\Models;

use App\Enum\UserRole;
use App\Enum\VerificationStatus;
use App\Models\IdentityVerification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['name', 'email', 'password', 'phone_number', 'role', 'is_verified', 'email_verified_at', 'avatar'])]
#[Guarded(['id', 'created_at', 'updated_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_verified' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    public function boardingHouses(): HasMany
    {
        return $this->hasMany(BoardingHouse::class, 'owner_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'tenant_id');
    }

    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class, 'tenant_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'tenant_id');
    }

    public function identityVerifications(): HasMany
    {
        return $this->hasMany(IdentityVerification::class);
    }

    // ─── Verification helpers ─────────────────────────────────────

    /**
     * Cek apakah user sudah fully verified.
     * - Tenant  : harus ada minimal 1 dokumen (KTP/KTM) yang approved.
     * - Owner   : harus ada KTP approved + minimal 1 bukti kepemilikan approved.
     */
    public function isFullyVerified(): bool
    {
        if ($this->is_verified) {
            return true;
        }

        $approved = $this->identityVerifications
            ->where('status', VerificationStatus::APPROVED)
            ->pluck('document_type');

        if ($this->role === UserRole::TENANT) {
            return $approved->contains(fn ($t) => in_array($t->value, ['ktp', 'ktm']));
        }

        if ($this->role === UserRole::OWNER) {
            $hasKtp        = $approved->contains(fn ($t) => $t->value === 'owner_ktp');
            $hasOwnership  = $approved->contains(
                fn ($t) => in_array($t->value, ['pbb', 'electricity_bill', 'water_bill'])
            );
            return $hasKtp && $hasOwnership;
        }

        return false;
    }

    /**
     * Apakah ada dokumen yang masih pending review?
     */
    public function hasPendingVerification(): bool
    {
        return $this->identityVerifications
            ->contains('status', VerificationStatus::PENDING);
    }
}
