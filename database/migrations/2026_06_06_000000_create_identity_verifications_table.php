<?php

use App\Enum\DocumentType;
use App\Enum\VerificationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel ini menyimpan dokumen verifikasi identitas user.
     * - Tenant  : 1 dokumen (KTP atau KTM)
     * - Owner   : 2 dokumen (KTP wajib + salah satu bukti kepemilikan)
     */
    public function up(): void
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relasi ke user
            $table->string('user_id', 36)->index();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // Jenis dokumen yang diupload
            $table->enum('document_type', array_column(DocumentType::cases(), 'value'));

            // Path file yang tersimpan di storage/app/private/verifications/
            $table->string('file_path');

            // Status per-dokumen
            $table->enum('status', array_column(VerificationStatus::cases(), 'value'))
                  ->default(VerificationStatus::PENDING->value);

            // Catatan dari admin saat menolak
            $table->text('admin_note')->nullable();

            // Admin yang memverifikasi
            $table->string('reviewed_by', 36)->nullable();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // Satu user hanya bisa punya satu record per jenis dokumen
            $table->unique(['user_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
    }
};
