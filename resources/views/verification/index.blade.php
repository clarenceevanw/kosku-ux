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
                        
                        <!-- Drag and Drop Zone -->
                        <div id="drop-zone" class="relative group flex flex-col items-center justify-center gap-3 px-6 py-10 border-2 border-dashed border-outline-variant rounded-2xl bg-surface hover:border-primary/50 hover:bg-primary-container/10 transition-all cursor-pointer overflow-hidden" onclick="document.getElementById('file').click()">
                            
                            <!-- Initial State -->
                            <div id="upload-prompt" class="flex flex-col items-center text-center pointer-events-none transition-opacity duration-300">
                                <div class="w-16 h-16 mb-2 rounded-full bg-primary-container/50 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-[32px]">cloud_upload</span>
                                </div>
                                <p class="text-sm font-semibold text-on-surface font-label">Klik atau Tarik file ke sini</p>
                                <p class="text-xs text-on-surface-variant mt-1">JPG, PNG, atau PDF (Maks. 5 MB)</p>
                            </div>

                            <!-- Preview State -->
                            <div id="preview-container" class="hidden absolute inset-0 bg-surface flex-col items-center justify-center z-10 p-2">
                                <img id="image-preview" src="" alt="Preview" class="hidden w-full h-full object-contain rounded-xl">
                                <div id="pdf-preview" class="hidden flex-col items-center justify-center w-full h-full">
                                    <span class="material-symbols-outlined text-red-500 text-[48px]">picture_as_pdf</span>
                                    <p class="mt-2 text-sm font-bold text-on-surface">Dokumen PDF</p>
                                </div>
                            </div>
                            
                            <!-- Hover Overlay for Preview -->
                            <div id="preview-overlay" class="hidden absolute inset-0 bg-black/60 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                <span class="material-symbols-outlined text-[32px] mb-1">change_circle</span>
                                <p class="text-sm font-semibold">Ganti File</p>
                            </div>

                            <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.pdf" required
                                class="hidden"
                                onchange="handleFileSelect(this)">
                        </div>

                        <!-- File Details Banner -->
                        <div id="file-details" class="hidden mt-3 p-3 bg-primary-container/20 border border-primary/20 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <span class="material-symbols-outlined text-primary text-[24px] shrink-0">draft</span>
                                <div class="min-w-0">
                                    <p id="file-name" class="text-sm font-semibold text-on-surface truncate pr-4">filename.jpg</p>
                                    <p id="file-size" class="text-xs text-on-surface-variant mt-0.5">1.2 MB</p>
                                </div>
                            </div>
                            <button type="button" onclick="removeFile(event)" class="w-8 h-8 rounded-full hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors shrink-0" title="Hapus File">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>

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

// ── Drag and Drop Logic ──
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-primary', 'bg-primary-container/10');
    dropZone.classList.remove('border-outline-variant', 'bg-surface');
}

function unhighlight(e) {
    dropZone.classList.remove('border-primary', 'bg-primary-container/10');
    dropZone.classList.add('border-outline-variant', 'bg-surface');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length) {
        fileInput.files = files;
        handleFileSelect(fileInput);
    }
}

function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Tampilkan detail file
        document.getElementById('file-details').classList.remove('hidden');
        document.getElementById('file-details').classList.add('flex');
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        
        // Handle Preview
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('upload-prompt').classList.add('hidden');
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('preview-container').classList.add('flex');
            document.getElementById('preview-overlay').classList.remove('hidden');
            
            if (file.type.startsWith('image/')) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('pdf-preview').classList.add('hidden');
                document.getElementById('pdf-preview').classList.remove('flex');
            } else if (file.type === 'application/pdf') {
                document.getElementById('image-preview').classList.add('hidden');
                document.getElementById('pdf-preview').classList.remove('hidden');
                document.getElementById('pdf-preview').classList.add('flex');
            }
        }
        
        reader.readAsDataURL(file);
    }
}

function removeFile(e) {
    e.stopPropagation();
    fileInput.value = '';
    
    // Reset UI
    document.getElementById('file-details').classList.add('hidden');
    document.getElementById('file-details').classList.remove('flex');
    
    document.getElementById('upload-prompt').classList.remove('hidden');
    document.getElementById('preview-container').classList.add('hidden');
    document.getElementById('preview-container').classList.remove('flex');
    document.getElementById('preview-overlay').classList.add('hidden');
    
    document.getElementById('image-preview').src = '';
}
</script>
@endpush
