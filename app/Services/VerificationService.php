<?php

namespace App\Services;

use App\Enum\DocumentType;
use App\Enum\UserRole;
use App\Enum\VerificationStatus;
use App\Models\IdentityVerification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * VerificationService
 *
 * Menangani semua logika bisnis terkait verifikasi identitas user.
 *  - Tenant : upload KTP atau KTM
 *  - Owner  : upload KTP + minimal 1 bukti kepemilikan (PBB / Listrik / Air)
 */
class VerificationService
{
    /**
     * Upload dokumen verifikasi dari user.
     * Jika dokumen dengan jenis yang sama pernah di-reject, file lama diganti.
     *
     * @param  User          $user
     * @param  DocumentType  $documentType
     * @param  UploadedFile  $file
     * @return IdentityVerification
     */
    public function uploadDocument(
        User $user,
        DocumentType $documentType,
        UploadedFile $file
    ): IdentityVerification {
        // Pastikan tipe dokumen sesuai role
        $this->assertDocumentAllowedForRole($user, $documentType);

        return DB::transaction(function () use ($user, $documentType, $file) {
            /** @var IdentityVerification|null $existing */
            $existing = IdentityVerification::where('user_id', $user->id)
                ->where('document_type', $documentType->value)
                ->first();

            // Hapus file lama jika ada
            if ($existing && Storage::disk('private')->exists($existing->file_path)) {
                Storage::disk('private')->delete($existing->file_path);
            }

            $path = $file->store("verifications/{$user->id}", 'private');

            $record = IdentityVerification::updateOrCreate(
                [
                    'user_id'       => $user->id,
                    'document_type' => $documentType->value,
                ],
                [
                    'file_path'   => $path,
                    'status'      => VerificationStatus::PENDING->value,
                    'admin_note'  => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                ]
            );

            return $record;
        });
    }

    /**
     * Admin menyetujui satu dokumen verifikasi.
     * Setelah disetujui, cek apakah user sudah fully verified → update is_verified.
     */
    public function approveDocument(
        IdentityVerification $verification,
        User $admin
    ): void {
        DB::transaction(function () use ($verification, $admin) {
            $verification->update([
                'status'      => VerificationStatus::APPROVED->value,
                'admin_note'  => null,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            // Reload relasi lalu cek apakah user sudah fully verified
            $user = $verification->user->load('identityVerifications');
            if ($user->isFullyVerified()) {
                $user->update(['is_verified' => true]);
            }
        });
    }

    /**
     * Admin menolak satu dokumen verifikasi dengan catatan alasan.
     * is_verified user direset ke false karena ada dokumen yang ditolak.
     */
    public function rejectDocument(
        IdentityVerification $verification,
        User $admin,
        string $note
    ): void {
        DB::transaction(function () use ($verification, $admin, $note) {
            $verification->update([
                'status'      => VerificationStatus::REJECTED->value,
                'admin_note'  => $note,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            // Jika ada dokumen yang ditolak, cabut status verified
            $verification->user->update(['is_verified' => false]);
        });
    }

    /**
     * Kembalikan ringkasan status verifikasi user untuk ditampilkan di view.
     *
     * @param  User  $user
     * @return array{
     *   documents: \Illuminate\Database\Eloquent\Collection,
     *   is_fully_verified: bool,
     *   has_pending: bool,
     *   required_types: DocumentType[],
     *   missing_types: DocumentType[],
     * }
     */
    public function getVerificationSummary(User $user): array
    {
        $user->load('identityVerifications');

        $requiredTypes = $user->role === UserRole::OWNER
            ? DocumentType::ownerTypes()
            : DocumentType::tenantTypes();

        $uploadedTypes = $user->identityVerifications
            ->pluck('document_type')
            ->toArray();

        $missingTypes = array_filter(
            $requiredTypes,
            fn (DocumentType $t) => ! in_array($t, $uploadedTypes)
        );

        return [
            'documents'         => $user->identityVerifications,
            'is_fully_verified' => $user->isFullyVerified(),
            'has_pending'       => $user->hasPendingVerification(),
            'required_types'    => $requiredTypes,
            'missing_types'     => array_values($missingTypes),
        ];
    }

    /**
     * Ambil semua verifikasi yang masih pending, dikelompokkan per user (untuk admin).
     */
    public function getPendingVerifications(): \Illuminate\Database\Eloquent\Collection
    {
        return IdentityVerification::with(['user', 'reviewer'])
            ->where('status', VerificationStatus::PENDING->value)
            ->orderBy('created_at')
            ->get();
    }

    // ─── Private helpers ──────────────────────────────────────────

    private function assertDocumentAllowedForRole(User $user, DocumentType $documentType): void
    {
        $allowed = $user->role === UserRole::OWNER
            ? DocumentType::ownerTypes()
            : DocumentType::tenantTypes();

        if (! in_array($documentType, $allowed)) {
            throw new \InvalidArgumentException(
                "Tipe dokumen [{$documentType->value}] tidak diizinkan untuk role [{$user->role->value}]."
            );
        }
    }
}
