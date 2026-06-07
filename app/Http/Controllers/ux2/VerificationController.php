<?php

namespace App\Http\Controllers\ux2;

use App\Enum\DocumentType;
use App\Http\Controllers\Controller;
use App\Models\IdentityVerification;
use App\Services\VerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * ux2\VerificationController
 *
 * Verifikasi dokumen untuk UX2 — menggunakan VerificationService yang sama
 * namun merender view ux2 dengan desain yang berbeda.
 */
class VerificationController extends Controller
{
    public function __construct(
        private readonly VerificationService $verificationService
    ) {}

    /**
     * GET /ux2/verification
     * Halaman utama verifikasi — menampilkan status & form upload.
     * Deteksi role dan tampilkan view yang sesuai.
     */
    public function index(): View
    {
        $user    = Auth::user()->load('identityVerifications');
        $summary = $this->verificationService->getVerificationSummary($user);
        $role    = $user->role->value;

        $view = $role === 'owner'
            ? 'ux2.verification.owner'
            : 'ux2.verification.tenant';

        return view($view, compact('user', 'summary'));
    }

    /**
     * POST /ux2/verification/upload
     * Upload satu dokumen verifikasi (shared logic).
     */
    public function upload(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'document_type' => ['required', 'string'],
            'file'          => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'document_type.required' => 'Pilih jenis dokumen terlebih dahulu.',
            'file.required'          => 'File dokumen wajib diupload.',
            'file.mimes'             => 'Format file harus JPG, PNG, atau PDF.',
            'file.max'               => 'Ukuran file maksimal 5 MB.',
        ]);

        try {
            $documentType = DocumentType::from($request->input('document_type'));
        } catch (\ValueError) {
            return back()->withErrors(['document_type' => 'Jenis dokumen tidak valid.']);
        }

        try {
            $this->verificationService->uploadDocument(
                $user,
                $documentType,
                $request->file('file')
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['document_type' => $e->getMessage()]);
        }

        return back()->with('success', "Dokumen {$documentType->label()} berhasil diupload dan menunggu verifikasi admin.");
    }

    /**
     * GET /ux2/verification/file/{verification}
     * Serve file dokumen (hanya pemilik atau admin).
     */
    public function serveFile(IdentityVerification $verification): StreamedResponse
    {
        $user = Auth::user();

        if ($verification->user_id !== $user->id && $user->role->value !== 'admin') {
            abort(403);
        }

        abort_unless(Storage::disk('private')->exists($verification->file_path), 404);

        return Storage::disk('private')->download($verification->file_path);
    }
}
