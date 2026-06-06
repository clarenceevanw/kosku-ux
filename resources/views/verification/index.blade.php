@extends('layouts.app')

@section('title', 'Verifikasi Identitas — KosKu')

@push('styles')
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp .45s ease both; }
    .fade-up-1 { animation-delay: .05s; }
    .fade-up-2 { animation-delay: .12s; }
    .fade-up-3 { animation-delay: .19s; }
    .fade-up-4 { animation-delay: .26s; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-surface py-20">

    <div class="max-w-4xl mx-auto px-4 md:px-8 py-8 space-y-8">

        {{-- ── Flash messages (handled by layout, but also inline for warning) ── --}}
        @if(session('warning'))
        <div class="flex items-center gap-3 px-5 py-4 bg-yellow-50 border border-yellow-200 rounded-2xl text-sm text-yellow-800 font-medium fade-up">
            <span class="material-symbols-outlined text-yellow-500 text-[20px]">warning</span>
            {{ session('warning') }}
        </div>
        @endif

        @if($errors->any())
        <div class="flex flex-col gap-1 px-5 py-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700 fade-up">
            <div class="flex items-center gap-2 font-semibold mb-1">
                <span class="material-symbols-outlined text-red-500 text-[18px]">error</span>
                Terjadi kesalahan:
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-red-600">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- ── Status Banner ── --}}
        <div class="fade-up fade-up-1">
            @if($summary['is_fully_verified'])
                <div class="flex items-center gap-4 px-6 py-5 bg-green-50 border border-green-200 rounded-2xl">
                    <span class="material-symbols-outlined text-green-500 text-[36px]" style="font-variation-settings:'FILL' 1">verified</span>
                    <div>
                        <p class="font-bold text-green-800 text-base">Identitas Terverifikasi</p>
                        <p class="text-green-700 text-sm mt-0.5">Akun Anda telah diverifikasi. Anda dapat menggunakan semua fitur KosKu.</p>
                    </div>
                </div>
            @elseif($summary['has_pending'])
                <div class="flex items-center gap-4 px-6 py-5 bg-yellow-50 border border-yellow-200 rounded-2xl">
                    <span class="material-symbols-outlined text-yellow-500 text-[36px]" style="font-variation-settings:'FILL' 1">pending</span>
                    <div>
                        <p class="font-bold text-yellow-800 text-base">Menunggu Verifikasi Admin</p>
                        <p class="text-yellow-700 text-sm mt-0.5">Dokumen Anda sedang ditinjau. Proses ini biasanya membutuhkan 1×24 jam.</p>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-4 px-6 py-5 bg-blue-50 border border-blue-200 rounded-2xl">
                    <span class="material-symbols-outlined text-blue-500 text-[36px]" style="font-variation-settings:'FILL' 1">assignment</span>
                    <div>
                        <p class="font-bold text-blue-800 text-base">Dokumen Belum Lengkap</p>
                        <p class="text-blue-700 text-sm mt-0.5">Upload semua dokumen yang diperlukan untuk menyelesaikan verifikasi akun Anda.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Dokumen yang Sudah Diupload ── --}}
        @if($summary['documents']->count() > 0)
        <div class="fade-up fade-up-2">
            <h2 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-3 font-label">Status Dokumen Anda</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($summary['documents'] as $doc)
                <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl p-4 flex flex-col gap-3 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp'])
                                    ? 'bg-primary-container'
                                    : 'bg-tertiary-fixed' }}">
                                <span class="material-symbols-outlined text-[20px]
                                    {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp'])
                                        ? 'text-on-primary-fixed'
                                        : 'text-on-tertiary-fixed' }}"
                                    style="font-variation-settings:'FILL' 1">
                                    {{ in_array($doc->document_type->value, ['ktp','ktm','owner_ktp']) ? 'badge' : 'home' }}
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-on-surface font-label leading-tight">{{ $doc->document_type->label() }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $doc->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Status Pill --}}
                        @if($doc->status->value === 'approved')
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700 border border-green-200 shrink-0">
                                <span class="material-symbols-outlined text-[13px]">check_circle</span> Disetujui
                            </span>
                        @elseif($doc->status->value === 'pending')
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200 shrink-0">
                                <span class="material-symbols-outlined text-[13px]">schedule</span> Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700 border border-red-200 shrink-0">
                                <span class="material-symbols-outlined text-[13px]">cancel</span> Ditolak
                            </span>
                        @endif
                    </div>

                    {{-- Admin note on reject --}}
                    @if($doc->isRejected() && $doc->admin_note)
                    <div class="flex gap-2 px-3 py-2.5 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                        <span class="material-symbols-outlined text-red-500 text-[15px] shrink-0 mt-0.5">info</span>
                        <span><strong>Alasan ditolak:</strong> {{ $doc->admin_note }}</span>
                    </div>
                    @endif

                    {{-- Reupload link --}}
                    @if(!$doc->isApproved())
                    <button type="button"
                        onclick="setDocType('{{ $doc->document_type->value }}')"
                        class="text-xs font-semibold text-primary hover:underline text-left transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">upload</span>
                        {{ $doc->isRejected() ? 'Upload ulang' : 'Ganti file' }}
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Persyaratan Dokumen ── --}}
        <div class="fade-up fade-up-3">
            <h2 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-3 font-label">
                Dokumen yang Diperlukan
                <span class="ml-1 text-xs font-normal normal-case text-on-surface-variant/70">
                    ({{ auth()->user()->role->value === 'owner' ? 'Pemilik Kos' : 'Penghuni' }})
                </span>
            </h2>

            @if(auth()->user()->role->value === 'tenant')
                <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-on-primary-fixed text-[20px]" style="font-variation-settings:'FILL' 1">badge</span>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface text-sm font-label">KTP <span class="font-normal text-on-surface-variant">atau</span> KTM <span class="text-red-500 text-xs font-normal">(wajib salah satu)</span></p>
                            <p class="text-on-surface-variant text-xs mt-1 leading-relaxed">
                                Upload foto KTP (Kartu Tanda Penduduk) atau KTM (Kartu Tanda Mahasiswa) yang masih berlaku, terlihat jelas dan tidak buram.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col gap-3">
                    <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-on-primary-fixed text-[20px]" style="font-variation-settings:'FILL' 1">badge</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface text-sm font-label">KTP Pemilik <span class="text-red-500 text-xs font-normal">(wajib)</span></p>
                                <p class="text-on-surface-variant text-xs mt-1 leading-relaxed">
                                    Upload foto KTP Anda sebagai pemilik kos yang masih berlaku dan terbaca jelas.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-tertiary-fixed flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-on-tertiary-fixed text-[20px]" style="font-variation-settings:'FILL' 1">home</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface text-sm font-label">Bukti Kepemilikan <span class="text-red-500 text-xs font-normal">(wajib salah satu)</span></p>
                                <p class="text-on-surface-variant text-xs mt-1 leading-relaxed mb-2">
                                    Upload salah satu dari dokumen berikut sebagai bukti kepemilikan properti kos:
                                </p>
                                <ul class="text-xs text-on-surface-variant space-y-1">
                                    <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span> Tagihan PBB (Pajak Bumi dan Bangunan)</li>
                                    <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span> Tagihan Listrik atas nama pemilik</li>
                                    <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span> Tagihan Air atas nama pemilik</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Form Upload ── --}}
        @if(!$summary['is_fully_verified'])
        <div class="fade-up fade-up-4">
            <h2 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-3 font-label">Upload Dokumen</h2>
            <div class="bg-surface-container-lowest border border-outline-variant/60 rounded-2xl p-6" id="upload-section">
                <form method="POST" action="{{ route('verification.upload') }}" enctype="multipart/form-data" id="upload-form" class="space-y-5">
                    @csrf

                    {{-- Jenis Dokumen --}}
                    <div>
                        <label for="document_type" class="block text-sm font-semibold text-on-surface mb-1.5 font-label">
                            Jenis Dokumen <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="document_type" id="document_type" required
                                class="w-full appearance-none bg-surface border border-outline-variant rounded-xl px-4 py-3 pr-10 text-sm text-on-surface font-body focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors cursor-pointer">
                                <option value="">— Pilih jenis dokumen —</option>
                                @if(auth()->user()->role->value === 'tenant')
                                    <option value="ktp"  {{ old('document_type') === 'ktp'  ? 'selected' : '' }}>KTP (Kartu Tanda Penduduk)</option>
                                    <option value="ktm"  {{ old('document_type') === 'ktm'  ? 'selected' : '' }}>KTM (Kartu Tanda Mahasiswa)</option>
                                @else
                                    <option value="owner_ktp"        {{ old('document_type') === 'owner_ktp'        ? 'selected' : '' }}>KTP Pemilik</option>
                                    <option value="pbb"              {{ old('document_type') === 'pbb'              ? 'selected' : '' }}>Tagihan PBB</option>
                                    <option value="electricity_bill" {{ old('document_type') === 'electricity_bill' ? 'selected' : '' }}>Tagihan Listrik</option>
                                    <option value="water_bill"       {{ old('document_type') === 'water_bill'       ? 'selected' : '' }}>Tagihan Air</option>
                                @endif
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px] pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    {{-- File Upload --}}
                    <div>
                        <label for="file" class="block text-sm font-semibold text-on-surface mb-1.5 font-label">
                            File Dokumen <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex flex-col items-center gap-3 px-6 py-8 border-2 border-dashed border-outline-variant rounded-xl hover:border-primary/50 hover:bg-primary-container/30 transition-colors cursor-pointer"
                             onclick="document.getElementById('file').click()">
                            <span class="material-symbols-outlined text-on-surface-variant text-[40px]">upload_file</span>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-on-surface font-label">Klik untuk pilih file</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">JPG, PNG, atau PDF · Maks. 5 MB</p>
                            </div>
                            <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.pdf" required
                                class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                                onchange="showFileName(this)">
                        </div>
                        <p id="file-name-display" class="hidden mt-2 text-xs text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-primary">description</span>
                            <span id="file-name-text"></span>
                        </p>
                        <p class="mt-2 text-xs text-on-surface-variant leading-relaxed">
                            Pastikan dokumen terlihat jelas, tidak buram, tidak terpotong, dan semua informasi terbaca.
                        </p>
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-primary text-on-primary font-semibold text-sm px-6 py-3.5 rounded-full hover:bg-primary/90 active:scale-[.98] transition-all duration-150 shadow-sm font-label">
                        <span class="material-symbols-outlined text-[18px]">upload</span>
                        Upload Dokumen
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>{{-- /max-w-4xl --}}
</div>
@endsection

@push('scripts')
<script>
function setDocType(val) {
    const el = document.getElementById('document_type');
    if (el) el.value = val;
    document.getElementById('upload-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function showFileName(input) {
    const display = document.getElementById('file-name-display');
    const text    = document.getElementById('file-name-text');
    if (input.files && input.files[0]) {
        text.textContent = input.files[0].name;
        display.classList.remove('hidden');
        display.classList.add('flex');
    }
}
</script>
@endpush
