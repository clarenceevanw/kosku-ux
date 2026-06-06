@extends('layouts.owner')

@section('title', 'Detail Verifikasi — ' . $user->name)

@section('content')

{{-- ── Back link ── --}}
<a href="{{ route('admin.verifications.index') }}"
   class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors mb-6">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
    Kembali ke Daftar Verifikasi
</a>

{{-- ── User Profile Card ── --}}
<div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl p-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-5">
    <div class="w-16 h-16 rounded-2xl bg-primary flex items-center justify-center text-on-primary text-2xl font-bold shrink-0 shadow-md">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div class="flex-1 min-w-0">
        <div class="flex flex-wrap items-center gap-2 mb-1">
            <h1 class="text-xl font-bold text-on-background font-headline">{{ $user->name }}</h1>
            @if($user->role->value === 'owner')
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-primary-container text-on-primary-container border border-outline-variant">Pemilik Kos</span>
            @else
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed border border-outline-variant">Penghuni</span>
            @endif
        </div>
        <p class="text-on-surface-variant text-sm">{{ $user->email }} · {{ $user->phone_number }}</p>
        <div class="mt-2">
            @if($summary['is_fully_verified'])
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-700 border border-green-200">
                    <span class="material-symbols-outlined text-[13px]">verified</span> Terverifikasi Penuh
                </span>
            @elseif($summary['has_pending'])
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                    <span class="material-symbols-outlined text-[13px]">schedule</span> Menunggu Review
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-red-100 text-red-700 border border-red-200">
                    <span class="material-symbols-outlined text-[13px]">cancel</span> Belum Terverifikasi
                </span>
            @endif
        </div>
    </div>
</div>

{{-- ── Documents ── --}}
<h2 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-4 font-label">
    Dokumen Identitas
    <span class="ml-1 normal-case font-normal text-on-surface-variant/60">({{ $summary['documents']->count() }} dokumen)</span>
</h2>

@forelse($summary['documents'] as $doc)
<div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl p-5 mb-4 space-y-4">

    {{-- Document header --}}
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp']) ? 'bg-primary-container' : 'bg-tertiary-fixed' }}">
                <span class="material-symbols-outlined text-[20px]
                    {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp']) ? 'text-on-primary-fixed' : 'text-on-tertiary-fixed' }}"
                    style="font-variation-settings:'FILL' 1">
                    {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp']) ? 'badge' : 'home' }}
                </span>
            </div>
            <div>
                <p class="font-bold text-on-surface font-label">{{ $doc->document_type->label() }}</p>
                <p class="text-xs text-on-surface-variant">Upload: {{ $doc->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>

        {{-- Status --}}
        @if($doc->status->value === 'approved')
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-green-100 text-green-700 border border-green-200">
                <span class="material-symbols-outlined text-[14px]">check_circle</span> Disetujui
            </span>
        @elseif($doc->status->value === 'pending')
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                <span class="material-symbols-outlined text-[14px]">schedule</span> Pending
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-red-100 text-red-700 border border-red-200">
                <span class="material-symbols-outlined text-[14px]">cancel</span> Ditolak
            </span>
        @endif
    </div>

    {{-- Admin note --}}
    @if($doc->admin_note)
    <div class="flex gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <span class="material-symbols-outlined text-red-500 text-[18px] shrink-0 mt-0.5">info</span>
        <span><strong>Catatan admin:</strong> {{ $doc->admin_note }}</span>
    </div>
    @endif

    {{-- Reviewer info --}}
    @if($doc->reviewed_by)
    <p class="text-xs text-on-surface-variant italic">
        Ditinjau oleh <strong>{{ $doc->reviewer->name ?? 'Admin' }}</strong>
        pada {{ $doc->reviewed_at?->translatedFormat('d F Y, H:i') }}
    </p>
    @endif

    {{-- Actions --}}
    <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-outline-variant/40">
        {{-- View file --}}
        <a href="{{ route('admin.verifications.file', $doc) }}" target="_blank"
           class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 rounded-full bg-surface-container border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-[16px]">visibility</span>
            Lihat / Unduh File
        </a>

        @if(!$doc->isApproved())
        <form method="POST" action="{{ route('admin.verifications.approve', $doc) }}" class="inline">
            @csrf
            <button type="submit"
                    onclick="return confirm('Setujui dokumen ini?')"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 rounded-full bg-green-100 border border-green-200 text-green-700 hover:bg-green-200 transition-colors">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                Setujui
            </button>
        </form>
        @endif

        @if(!$doc->isRejected())
        <button type="button"
                onclick="openRejectModal('{{ $doc->id }}', '{{ addslashes($doc->document_type->label()) }}')"
                class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 rounded-full bg-red-100 border border-red-200 text-red-700 hover:bg-red-200 transition-colors">
            <span class="material-symbols-outlined text-[16px]">cancel</span>
            Tolak
        </button>
        @endif
    </div>

</div>
@empty
<div class="flex flex-col items-center justify-center py-16 gap-3 text-center bg-surface-container-lowest border border-outline-variant/60 rounded-2xl">
    <span class="material-symbols-outlined text-on-surface-variant text-[40px]">inbox</span>
    <p class="font-semibold text-on-surface">Belum ada dokumen yang diupload.</p>
</div>
@endforelse

{{-- ── Reject Modal ── --}}
<div id="rejectModal"
     class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     onclick="if(event.target===this) closeRejectModal()">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-red-600 text-[20px]" style="font-variation-settings:'FILL' 1">cancel</span>
            </div>
            <div>
                <h3 class="font-bold text-on-surface font-headline text-base">Tolak Dokumen</h3>
                <p id="rejectModalSubtitle" class="text-on-surface-variant text-xs"></p>
            </div>
        </div>

        <form method="POST" id="rejectForm" class="space-y-4">
            @csrf
            <div>
                <label for="admin_note" class="block text-sm font-semibold text-on-surface mb-1.5 font-label">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="admin_note" id="admin_note" required rows="3"
                    placeholder="Contoh: Foto tidak jelas, mohon upload ulang dengan kualitas lebih tinggi..."
                    class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface font-body placeholder:text-on-surface-variant/50 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none transition-colors"></textarea>
                <p class="text-xs text-on-surface-variant mt-1">Alasan ini akan ditampilkan kepada pengguna agar mereka bisa upload ulang dengan benar.</p>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectModal()"
                    class="px-4 py-2.5 rounded-full text-sm font-semibold text-on-surface-variant bg-surface-container hover:bg-surface-container-high transition-colors border border-outline-variant">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                    Tolak Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openRejectModal(docId, docLabel) {
    document.getElementById('rejectModalSubtitle').textContent = docLabel;
    document.getElementById('rejectForm').action = `/admin/verifications/${docId}/reject`;
    document.getElementById('admin_note').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('admin_note').focus();
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endpush
