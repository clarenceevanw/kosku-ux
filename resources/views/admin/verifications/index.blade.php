@extends('layouts.owner')

@section('title', 'Review Verifikasi')

@section('content')

{{-- ── Page Header ── --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-on-background font-headline">Review Verifikasi Identitas</h1>
    <p class="text-on-surface-variant text-sm mt-1 font-body">Tinjau dan verifikasi dokumen identitas yang diupload pengguna.</p>
</div>

{{-- ── Stats Chips ── --}}
@php $grouped = $pendingVerifications->groupBy('user_id'); @endphp
<div class="flex flex-wrap gap-3 mb-6">
    <div class="inline-flex items-center gap-2 bg-yellow-50 border border-yellow-200 px-4 py-2 rounded-full text-sm font-semibold text-yellow-700">
        <span class="material-symbols-outlined text-yellow-500 text-[18px]">schedule</span>
        {{ $pendingVerifications->count() }} Dokumen Pending
    </div>
    <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 px-4 py-2 rounded-full text-sm font-semibold text-blue-700">
        <span class="material-symbols-outlined text-blue-500 text-[18px]">group</span>
        {{ $grouped->count() }} User Menunggu
    </div>
</div>

{{-- ── Empty State ── --}}
@if($grouped->isEmpty())
<div class="flex flex-col items-center justify-center py-20 gap-4 text-center">
    <div class="w-20 h-20 rounded-2xl bg-surface-container-high flex items-center justify-center">
        <span class="material-symbols-outlined text-on-surface-variant text-[40px]">task_alt</span>
    </div>
    <div>
        <p class="font-bold text-on-surface text-lg font-headline">Tidak Ada Verifikasi Pending</p>
        <p class="text-on-surface-variant text-sm mt-1">Semua dokumen telah ditinjau.</p>
    </div>
</div>

@else

{{-- ── User Groups ── --}}
<div class="space-y-4">
    @foreach($grouped as $userId => $docs)
        @php $user = $docs->first()->user; @endphp
        <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl overflow-hidden hover:shadow-sm transition-shadow">

            {{-- User Header --}}
            <div class="flex items-center gap-4 px-6 py-4 border-b border-outline-variant/40">
                <div class="w-11 h-11 rounded-full bg-primary flex items-center justify-center text-on-primary font-bold text-base shrink-0 shadow-sm">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-on-surface text-sm font-label truncate">{{ $user->name }}</p>
                    <p class="text-on-surface-variant text-xs truncate">{{ $user->email }} · {{ $user->phone_number }}</p>
                </div>
                {{-- Role badge --}}
                @if($user->role->value === 'owner')
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-primary-container text-on-primary-container border border-outline-variant shrink-0">Pemilik</span>
                @else
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed border border-outline-variant shrink-0">Penghuni</span>
                @endif
                <a href="{{ route('admin.verifications.show', $user) }}"
                   class="text-xs font-semibold text-primary hover:underline shrink-0 flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                    Detail
                </a>
            </div>

            {{-- Documents Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-surface-container/50">
                            <th class="text-left px-6 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-label">Jenis Dokumen</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-label">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-label">Upload</th>
                            <th class="text-right px-6 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-label">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @foreach($docs as $doc)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                        {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp']) ? 'bg-primary-container' : 'bg-tertiary-fixed' }}">
                                        <span class="material-symbols-outlined text-[16px]
                                            {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp']) ? 'text-on-primary-fixed' : 'text-on-tertiary-fixed' }}"
                                            style="font-variation-settings:'FILL' 1">
                                            {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp']) ? 'badge' : 'home' }}
                                        </span>
                                    </div>
                                    <span class="font-semibold text-on-surface font-label">{{ $doc->document_type->label() }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full
                                    bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    Pending
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-on-surface-variant">
                                {{ $doc->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    {{-- View file --}}
                                    <a href="{{ route('admin.verifications.file', $doc) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-surface-container border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">visibility</span>
                                        Lihat File
                                    </a>
                                    {{-- Approve --}}
                                    <form method="POST" action="{{ route('admin.verifications.approve', $doc) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Setujui dokumen {{ addslashes($doc->document_type->label()) }} ini?')"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-green-100 border border-green-200 text-green-700 hover:bg-green-200 transition-colors">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            Setujui
                                        </button>
                                    </form>
                                    {{-- Reject --}}
                                    <button type="button"
                                            onclick="openRejectModal('{{ $doc->id }}', '{{ addslashes($doc->document_type->label()) }}')"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-red-100 border border-red-200 text-red-700 hover:bg-red-200 transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">cancel</span>
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    @endforeach
</div>
@endif

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
