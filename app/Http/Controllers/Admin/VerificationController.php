<?php

namespace App\Http\Controllers\Admin;

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
 * Admin\VerificationController
 *
 * Mengurus panel review verifikasi untuk admin:
 *  - Melihat daftar pending
 *  - Approve / reject dokumen
 *  - Preview file dokumen
 */
class VerificationController extends Controller
{
    public function __construct(
        private readonly VerificationService $verificationService
    ) {}

    /**
     * GET /admin/verifications
     * Daftar semua verifikasi yang masih pending.
     */
    public function index(): View
    {
        $pendingVerifications = $this->verificationService->getPendingVerifications();

        return view('admin.verifications.index', compact('pendingVerifications'));
    }

    /**
     * GET /admin/verifications/{user}
     * Detail semua dokumen milik satu user.
     */
    public function show(\App\Models\User $user): View
    {
        $user->load('identityVerifications.reviewer');
        $summary = $this->verificationService->getVerificationSummary($user);

        return view('admin.verifications.show', compact('user', 'summary'));
    }

    /**
     * POST /admin/verifications/{verification}/approve
     * Admin menyetujui dokumen.
     */
    public function approve(IdentityVerification $verification): RedirectResponse
    {
        $this->verificationService->approveDocument($verification, Auth::user());

        return back()->with('success', "Dokumen {$verification->document_type->label()} telah disetujui.");
    }

    /**
     * POST /admin/verifications/{verification}/reject
     * Admin menolak dokumen dengan catatan alasan.
     */
    public function reject(Request $request, IdentityVerification $verification): RedirectResponse
    {
        $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ], [
            'admin_note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $this->verificationService->rejectDocument(
            $verification,
            Auth::user(),
            $request->input('admin_note')
        );

        return back()->with('success', "Dokumen {$verification->document_type->label()} telah ditolak.");
    }

    /**
     * GET /admin/verifications/{verification}/file
     * Admin preview file dokumen.
     */
    public function serveFile(IdentityVerification $verification): StreamedResponse
    {
        abort_unless(Storage::disk('private')->exists($verification->file_path), 404);

        return Storage::disk('private')->download($verification->file_path);
    }
}
