<?php

namespace App\Http\Controllers;

use App\Enum\DocumentType;
use App\Models\IdentityVerification;
use App\Services\VerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * VerificationController
 *
 * Mengurus halaman upload & status verifikasi untuk tenant dan owner.
 * Halaman review admin ada di Admin\VerificationController.
 */
class VerificationController extends Controller
{
    public function __construct(
        private readonly VerificationService $verificationService
    ) {}

    /**
     * GET /verification
     * Halaman utama verifikasi user — menampilkan status & form upload.
     */
    public function index(): View
    {
        $user    = Auth::user()->load('identityVerifications');
        $summary = $this->verificationService->getVerificationSummary($user);

        return view('verification.index', compact('user', 'summary'));
    }

    /**
     * POST /verification/upload
     * Upload satu dokumen verifikasi.
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
     * GET /verification/file/{verification}
     * Download / view file dokumen (hanya pemilik dokumen atau admin).
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
