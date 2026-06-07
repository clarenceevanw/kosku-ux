@extends('layouts.ux2.tenant')

@section('title', 'Verifikasi Identitas — Penghuni — KosKu')

@section('styles')
<style>
    /* ── ANIMATIONS ───────────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in  { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.92); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes shimmer { from { left:-80%; } to { left:140%; } }
    @keyframes badge-pulse {
        0%,100% { transform: scale(1); }
        50%      { transform: scale(1.06); }
    }
    @keyframes glow-ring {
        0%,100% { box-shadow: 0 0 0 0 rgba(47,143,121,0.5); }
        50%      { box-shadow: 0 0 0 12px rgba(47,143,121,0); }
    }
    @keyframes progress-bar {
        from { width: 0%; }
    }
    @keyframes float-doc {
        0%,100% { transform: translateY(0); }
        50%      { transform: translateY(-8px); }
    }

    .anim-fade-up  { animation: fade-up  0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in  { animation: fade-in  0.4s ease both; }
    .anim-scale-in { animation: scale-in 0.5s cubic-bezier(.22,.68,0,1.2) both; }
    .d1{animation-delay:.06s}.d2{animation-delay:.13s}
    .d3{animation-delay:.20s}.d4{animation-delay:.27s}.d5{animation-delay:.34s}

    /* ── SCROLL REVEAL ───────────────────────── */
    .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1),
                    transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .rev-d1{transition-delay:.08s} .rev-d2{transition-delay:.18s} .rev-d3{transition-delay:.28s}

    /* ── VERIFICATION CARD (floating) ───────── */
    .verif-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        box-shadow: var(--ux2-shadow-soft);
        overflow: hidden;
    }
    .verif-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--ux2-line);
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── HERO BANNER ─────────────────────────── */
    .verif-hero {
        border-radius: 16px;
        padding: 32px;
        position: relative;
        overflow: hidden;
        min-height: 180px;
        display: flex; align-items: center;
    }
    .verif-hero.hero-done {
        background: linear-gradient(135deg, var(--ux2-primary) 0%, #0a3b38 100%);
    }
    .verif-hero.hero-pending {
        background: linear-gradient(135deg, #854d0e 0%, #713f12 100%);
    }
    .verif-hero.hero-incomplete {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%);
    }
    .verif-hero::before {
        content:'';
        position:absolute; inset:0;
        background-image:
            radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 0%, transparent 50%),
            radial-gradient(circle at 20% 80%, rgba(255,255,255,0.05) 0%, transparent 50%);
    }
    .verif-hero::after {
        content:'';
        position:absolute; top:-40px; right:-40px;
        width:200px; height:200px;
        border-radius:50%;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
    }
    .hero-icon-wrap {
        width: 72px; height: 72px;
        border-radius: 20px;
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        animation: float-doc 4.5s ease-in-out infinite;
    }

    /* ── IDENTITY CARD VISUAL ────────────────── */
    .id-card-visual {
        width: 100%;
        border-radius: 14px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);
        color: #fff;
        aspect-ratio: 8/5;
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .id-card-visual::before {
        content:'';
        position:absolute; top:-30px; right:-30px;
        width:120px; height:120px; border-radius:50%;
        background:rgba(255,255,255,0.06);
        border:1px solid rgba(255,255,255,0.1);
    }
    .id-card-visual::after {
        content:'';
        position:absolute; bottom:-20px; left:-20px;
        width:100px; height:100px; border-radius:50%;
        background:rgba(255,255,255,0.04);
    }
    .id-chip {
        width: 40px; height: 28px;
        border-radius: 6px;
        background: linear-gradient(135deg, #f2bd5e, #e8a832);
    }

    /* ── STEP TRACKER (horizontal) ───────────── */
    .step-tracker {
        display: flex; align-items: flex-start; gap: 0;
        position: relative;
    }
    .step-track-item {
        flex: 1; display: flex; flex-direction: column; align-items: center;
        position: relative;
    }
    .step-track-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 18px; left: 50%; width: 100%;
        height: 2px; background: var(--ux2-line);
        z-index: 0;
    }
    .step-track-item.done:not(:last-child)::after { background: var(--ux2-secondary); }
    .step-dot {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid var(--ux2-line);
        background: #fff;
        font-size: 13px; font-weight: 800;
        color: var(--ux2-muted);
        z-index: 1;
        transition: all .3s ease;
    }
    .step-track-item.done .step-dot {
        background: var(--ux2-secondary);
        border-color: var(--ux2-secondary);
        color: #fff;
        animation: glow-ring 2.5s ease-in-out infinite;
    }
    .step-track-item.pending .step-dot {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }
    .step-track-item.error .step-dot {
        background: var(--ux2-coral);
        border-color: var(--ux2-coral);
        color: #fff;
    }

    /* ── DOCUMENT PILL CARD ──────────────────── */
    .doc-pill {
        background: #fff;
        border: 1.5px solid var(--ux2-line);
        border-radius: 14px;
        padding: 16px 18px;
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }
    .doc-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15,42,39,0.1);
    }
    .doc-pill.approved { border-color: var(--ux2-secondary); }
    .doc-pill.pending  { border-color: #f59e0b; }
    .doc-pill.rejected { border-color: var(--ux2-coral); }

    /* ── STATUS BADGE ────────────────────────── */
    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 999px;
        font-size: 11px; font-weight: 700;
    }
    .status-badge.approved { background: rgba(47,143,121,0.12); color: var(--ux2-secondary); border: 1px solid rgba(47,143,121,0.3); }
    .status-badge.pending  { background: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
    .status-badge.rejected { background: var(--ux2-coral-soft); color: var(--ux2-coral); border: 1px solid rgba(217,95,85,0.3); }

    /* ── UPLOAD PANEL ────────────────────────── */
    .upload-panel {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        box-shadow: var(--ux2-shadow-soft);
    }
    .upload-panel-header {
        padding: 18px 22px;
        background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);
        border-radius: 16px 16px 0 0;
    }
    .drop-zone {
        border: 2px dashed var(--ux2-line);
        border-radius: 12px;
        padding: 32px 20px;
        text-align: center;
        cursor: pointer;
        position: relative;
        transition: all .22s ease;
    }
    .drop-zone:hover, .drop-zone.over {
        border-color: var(--ux2-secondary);
        background: var(--ux2-primary-soft);
    }
    .drop-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }
    .drop-icon { font-size: 44px; color: var(--ux2-secondary); margin-bottom: 12px; display: block; }

    /* ── FORM SELECT ─────────────────────────── */
    .form-select-ux2 {
        width: 100%;
        background: #fff;
        border: 1.5px solid var(--ux2-line);
        border-radius: 10px;
        padding: 12px 40px 12px 16px;
        font-size: 14px; font-weight: 500;
        color: var(--ux2-ink);
        appearance: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-select-ux2:focus {
        outline: none;
        border-color: var(--ux2-secondary);
        box-shadow: 0 0 0 3px rgba(47,143,121,0.18);
    }

    /* ── BTN PRIMARY ─────────────────────────── */
    .btn-primary-ux2 {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 14px;
        background: var(--ux2-primary);
        color: #fff; border: none;
        border-radius: 12px;
        font-size: 14px; font-weight: 700;
        cursor: pointer;
        position: relative; overflow: hidden;
        transition: background .2s ease, transform .15s ease;
    }
    .btn-primary-ux2:hover { background: var(--ux2-primary-deep); transform: translateY(-1px); }
    .btn-primary-ux2:active { transform: scale(0.98); }
    .btn-primary-ux2::after {
        content:''; position:absolute; top:0; left:-80%;
        width:55%; height:100%;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.22),transparent);
        transform:skewX(-18deg);
        animation: shimmer 2.8s ease-in-out 1s infinite;
    }

    /* ── FILE RESULT ─────────────────────────── */
    .file-result {
        display: none;
        align-items: center; gap: 12px;
        padding: 12px 16px;
        background: var(--ux2-primary-soft);
        border: 1px solid var(--ux2-secondary-soft);
        border-radius: 10px;
        margin-top: 12px;
    }
    .file-result.show { display: flex; }
</style>
@endsection

@section('content')

@php
    $docs       = $summary['documents'];
    $isVerified = $summary['is_fully_verified'];
    $hasPending = $summary['has_pending'];
    $approvedCount = $docs->filter(fn($d) => $d->isApproved())->count();
    $totalCount    = $docs->count();
    $pct = $totalCount > 0 ? round($approvedCount / max($totalCount,1) * 100) : 0;
@endphp

{{-- ════ PAGE TITLE ════ --}}
<div class="mb-lg anim-fade-up">
    <div class="flex items-center gap-sm mb-xs">
        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:var(--ux2-primary-soft);">
            <span class="material-symbols-outlined text-[16px]" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">shield_person</span>
        </div>
        <span style="font-size:12px; font-weight:700; color:var(--ux2-muted); text-transform:uppercase; letter-spacing:.07em;">Verifikasi Identitas</span>
    </div>
    <h1 style="font-size:26px; font-weight:800; color:var(--ux2-ink); line-height:1.2;">Dokumen Saya</h1>
    <p style="font-size:14px; color:var(--ux2-muted); margin-top:4px;">
        Lengkapi identitas untuk mengakses semua fitur penghuni KosKu.
    </p>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div class="anim-fade-up mb-md flex items-center gap-3 p-md rounded-xl"
    style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
    <span class="material-symbols-outlined" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">check_circle</span>
    <p style="font-size:14px; color:var(--ux2-primary); font-weight:600;">{{ session('success') }}</p>
</div>
@endif
@if(session('error') || $errors->any())
<div class="anim-fade-up mb-md flex flex-col gap-1 p-md rounded-xl"
    style="background:var(--ux2-coral-soft); border:1px solid var(--ux2-coral);">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined" style="color:var(--ux2-coral); font-variation-settings:'FILL' 1;">error</span>
        <p style="font-size:14px; color:var(--ux2-coral); font-weight:600;">{{ session('error') ?? 'Terjadi kesalahan.' }}</p>
    </div>
    @if($errors->any())
    <ul style="font-size:13px; color:var(--ux2-coral); padding-left:8px; margin-top:4px;">
        @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
    </ul>
    @endif
</div>
@endif

{{-- ════ HERO STATUS ════ --}}
<div class="anim-fade-up d2 mb-lg">
    <div class="verif-hero {{ $isVerified ? 'hero-done' : ($hasPending ? 'hero-pending' : 'hero-incomplete') }}">
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-lg w-full">

            {{-- ID Card Visual (decorative) --}}
            <div class="hero-icon-wrap">
                <span class="material-symbols-outlined text-4xl" style="color:#fff; font-variation-settings:'FILL' 1;">
                    {{ $isVerified ? 'verified_user' : ($hasPending ? 'hourglass_top' : 'badge') }}
                </span>
            </div>

            {{-- Text --}}
            <div class="flex-1 text-center md:text-left">
                <p style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.55); text-transform:uppercase; letter-spacing:.08em; margin-bottom:6px;">
                    Status Identitas Anda
                </p>
                <h2 style="font-size:24px; font-weight:800; color:#fff; line-height:1.2; margin-bottom:8px;">
                    @if($isVerified) Identitas Terverifikasi ✓
                    @elseif($hasPending) Sedang Diproses Admin
                    @else Belum Diverifikasi
                    @endif
                </h2>
                <p style="font-size:13px; color:rgba(255,255,255,0.65); line-height:1.6;">
                    @if($isVerified)
                        Selamat! Akun Anda telah diverifikasi penuh. Nikmati semua fitur KosKu.
                    @elseif($hasPending)
                        Dokumen Anda sedang ditinjau tim verifikasi. Estimasi 1×24 jam kerja.
                    @else
                        Upload KTP atau KTM untuk memverifikasi identitas Anda sebagai penghuni.
                    @endif
                </p>
            </div>

            {{-- Progress ring for non-verified --}}
            @if(!$isVerified && $totalCount > 0)
            <div class="flex-shrink-0 text-center">
                <div style="width:90px; height:90px; position:relative; margin:0 auto;">
                    <svg width="90" height="90" style="transform:rotate(-90deg);">
                        <circle cx="45" cy="45" r="36" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="8"/>
                        <circle cx="45" cy="45" r="36" fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="8"
                            stroke-dasharray="{{ round(2 * 3.14159 * 36) }}"
                            stroke-dashoffset="{{ round(2 * 3.14159 * 36 * (1 - $pct/100)) }}"
                            stroke-linecap="round"
                            style="transition: stroke-dashoffset 1.2s cubic-bezier(.22,.68,0,1);"/>
                    </svg>
                    <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                        <span style="font-size:20px; font-weight:800; color:#fff;">{{ $pct }}%</span>
                        <span style="font-size:9px; font-weight:600; color:rgba(255,255,255,0.55);">LENGKAP</span>
                    </div>
                </div>
            </div>
            @elseif($isVerified)
            <div class="flex-shrink-0">
                <div class="w-20 h-20 rounded-full flex items-center justify-center"
                    style="background:rgba(255,255,255,0.15); border:2px solid rgba(255,255,255,0.3);">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <path d="M8 20 L16 28 L32 12" stroke="#fff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"
                            stroke-dasharray="60" stroke-dashoffset="0"
                            style="animation: none;"/>
                    </svg>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ════ CONTENT GRID ════ --}}
<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_340px] gap-lg">

    {{-- ── LEFT COLUMN ── --}}
    <div class="flex flex-col gap-lg">

        {{-- ── UPLOADED DOCS ── --}}
        @if($docs->count() > 0)
        <div class="verif-card reveal">
            <div class="verif-card-header">
                <div class="flex items-center gap-sm">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:var(--ux2-primary-soft);">
                        <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">description</span>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:var(--ux2-ink);">Dokumen Terupload</h3>
                        <p style="font-size:12px; color:var(--ux2-muted);">{{ $docs->count() }} dokumen</p>
                    </div>
                </div>
                <span class="status-badge {{ $isVerified ? 'approved' : ($hasPending ? 'pending' : 'rejected') }}">
                    <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1;">
                        {{ $isVerified ? 'check_circle' : ($hasPending ? 'schedule' : 'pending_actions') }}
                    </span>
                    {{ $isVerified ? 'Lengkap' : ($hasPending ? 'Menunggu' : 'Perlu Upload') }}
                </span>
            </div>

            <div class="p-md flex flex-col gap-sm">
                @foreach($docs as $doc)
                @php
                    $st = $doc->status->value;
                    $pillClass = $st === 'approved' ? 'approved' : ($st === 'pending' ? 'pending' : 'rejected');
                    $stLabel   = $st === 'approved' ? 'Disetujui' : ($st === 'pending' ? 'Menunggu' : 'Ditolak');
                    $stIcon    = $st === 'approved' ? 'verified' : ($st === 'pending' ? 'schedule' : 'cancel');
                @endphp
                <div class="doc-pill {{ $pillClass }}">
                    <div class="flex items-center justify-between gap-sm">
                        <div class="flex items-center gap-sm">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                style="background:var(--ux2-{{ $st === 'approved' ? 'primary' : ($st === 'pending' ? 'accent' : 'coral') }}-soft;">
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1; color:var(--ux2-{{ $st === 'approved' ? 'secondary' : ($st === 'pending' ? 'accent' : 'coral') }});">badge</span>
                            </div>
                            <div>
                                <p style="font-size:14px; font-weight:700; color:var(--ux2-ink);">{{ $doc->document_type->label() }}</p>
                                <p style="font-size:12px; color:var(--ux2-muted);">{{ $doc->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="status-badge {{ $pillClass }}">
                            <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1;">{{ $stIcon }}</span>
                            {{ $stLabel }}
                        </span>
                    </div>

                    {{-- Rejection note --}}
                    @if($doc->isRejected() && $doc->admin_note)
                    <div class="flex items-start gap-2 mt-sm p-sm rounded-xl" style="background:var(--ux2-coral-soft); border:1px solid rgba(217,95,85,0.25);">
                        <span class="material-symbols-outlined text-[14px] mt-0.5 flex-shrink-0" style="color:var(--ux2-coral);">info</span>
                        <p style="font-size:12px; color:var(--ux2-coral); line-height:1.5;">
                            <strong>Alasan ditolak:</strong> {{ $doc->admin_note }}
                        </p>
                    </div>
                    @endif

                    {{-- Re-upload --}}
                    @if(!$doc->isApproved())
                    <div class="mt-sm">
                        <button type="button"
                            onclick="triggerReupload('{{ $doc->document_type->value }}')"
                            class="inline-flex items-center gap-1 text-xs font-bold transition-colors"
                            style="color:var(--ux2-secondary);">
                            <span class="material-symbols-outlined text-[13px]">upload_file</span>
                            {{ $doc->isRejected() ? 'Upload ulang' : 'Ganti file' }}
                        </button>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── UPLOAD FORM ── --}}
        @if(!$isVerified)
        <div class="upload-panel reveal rev-d1" id="upload-section">
            {{-- Colored header --}}
            <div class="upload-panel-header">
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25);">
                        <span class="material-symbols-outlined" style="color:#fff; font-variation-settings:'FILL' 1;">upload_file</span>
                    </div>
                    <div>
                        <p style="font-size:15px; font-weight:700; color:#fff;">Upload Dokumen Identitas</p>
                        <p style="font-size:12px; color:rgba(255,255,255,0.6);">JPG, PNG, PDF · Maks. 5 MB</p>
                    </div>
                </div>
            </div>

            <div class="p-md">
                <form method="POST" action="{{ route('ux2.verification.upload') }}" enctype="multipart/form-data" id="verif-form">
                    @csrf

                    {{-- Doc type --}}
                    <div class="mb-md">
                        <label style="display:block; font-size:13px; font-weight:700; color:var(--ux2-ink); margin-bottom:8px;">
                            Jenis Dokumen <span style="color:var(--ux2-coral);">*</span>
                        </label>
                        <div style="position:relative;">
                            <select name="document_type" id="document_type" required class="form-select-ux2">
                                <option value="">— Pilih jenis —</option>
                                <option value="ktp" {{ old('document_type') === 'ktp' ? 'selected' : '' }}>
                                    KTP (Kartu Tanda Penduduk)
                                </option>
                                <option value="ktm" {{ old('document_type') === 'ktm' ? 'selected' : '' }}>
                                    KTM (Kartu Tanda Mahasiswa)
                                </option>
                            </select>
                            <span class="material-symbols-outlined" style="position:absolute; right:14px; top:50%; transform:translateY(-50%); color:var(--ux2-muted); font-size:18px; pointer-events:none;">expand_more</span>
                        </div>
                    </div>

                    {{-- Drop zone --}}
                    <div class="mb-md">
                        <label style="display:block; font-size:13px; font-weight:700; color:var(--ux2-ink); margin-bottom:8px;">
                            File Dokumen <span style="color:var(--ux2-coral);">*</span>
                        </label>
                        <div class="drop-zone" id="drop-zone"
                            ondragover="event.preventDefault(); this.classList.add('over')"
                            ondragleave="this.classList.remove('over')"
                            ondrop="handleFileDrop(event)">
                            <input type="file" name="file" id="file-input"
                                accept=".jpg,.jpeg,.png,.pdf" required
                                onchange="onFileChosen(this)">
                            <span class="material-symbols-outlined drop-icon" style="font-variation-settings:'FILL' 1;">upload_file</span>
                            <p style="font-size:15px; font-weight:700; color:var(--ux2-ink); margin-bottom:4px;">Seret file ke sini</p>
                            <p style="font-size:12px; color:var(--ux2-muted);">atau <span style="color:var(--ux2-secondary); font-weight:600;">klik untuk memilih file</span></p>
                        </div>

                        {{-- File result preview --}}
                        <div class="file-result" id="file-result">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:var(--ux2-primary-soft);">
                                <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">description</span>
                            </div>
                            <div style="flex:1; min-width:0;">
                                <p id="chosen-name" style="font-size:13px; font-weight:700; color:var(--ux2-ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></p>
                                <p id="chosen-size" style="font-size:11px; color:var(--ux2-muted); margin-top:2px;"></p>
                            </div>
                            <button type="button" onclick="clearFileChoice()" style="flex-shrink:0; color:var(--ux2-coral);">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>

                        <p style="font-size:11px; color:var(--ux2-muted); margin-top:8px; line-height:1.5;">
                            Pastikan dokumen tidak blur, tidak terpotong, dan semua informasi terbaca jelas.
                        </p>
                    </div>

                    <button type="submit" class="btn-primary-ux2">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">upload</span>
                        Upload Sekarang
                    </button>
                </form>
            </div>
        </div>
        @else
        {{-- Already verified state --}}
        <div class="reveal rev-d1 flex flex-col items-center justify-center py-xl px-md text-center"
            style="background:#fff; border:1px solid var(--ux2-line); border-radius:16px; box-shadow:var(--ux2-shadow-soft);">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-md"
                style="background:var(--ux2-primary-soft); animation: glow-ring 3s ease-in-out infinite;">
                <span class="material-symbols-outlined text-5xl" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">how_to_reg</span>
            </div>
            <h3 style="font-size:20px; font-weight:800; color:var(--ux2-ink); margin-bottom:8px;">Identitas Sudah Diverifikasi!</h3>
            <p style="font-size:14px; color:var(--ux2-muted); max-width:280px; line-height:1.6; margin-bottom:20px;">
                Semua dokumen Anda telah disetujui admin. Akun penghuni Anda aktif penuh.
            </p>
            <a href="{{ route('ux2.tenant.dashboard') }}"
                class="btn-primary-ux2"
                style="max-width:200px; text-decoration:none;">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">home</span>
                Ke Dashboard
            </a>
        </div>
        @endif

    </div>{{-- end left --}}

    {{-- ── RIGHT COLUMN ── --}}
    <div class="flex flex-col gap-lg">

        {{-- ID Card Visual --}}
        <div class="reveal anim-scale-in d3">
            <div class="id-card-visual">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p style="font-size:9px; font-weight:700; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:.08em;">KosKu Tenant ID</p>
                        <p style="font-size:14px; font-weight:700; color:#fff; margin-top:2px;">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                        style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2);">
                        <span class="material-symbols-outlined text-[20px]" style="color:#fff; font-variation-settings:'FILL' 1;">home_work</span>
                    </div>
                </div>
                <div class="relative z-10">
                    <div class="id-chip mb-sm" style="display:inline-block;"></div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p style="font-size:9px; color:rgba(255,255,255,0.45); text-transform:uppercase; letter-spacing:.06em;">Status</p>
                            <p style="font-size:13px; font-weight:700; color:#fff;">
                                {{ $isVerified ? '✓ Terverifikasi' : ($hasPending ? '⏳ Menunggu' : '⚠ Belum Lengkap') }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:9px; color:rgba(255,255,255,0.45); text-transform:uppercase; letter-spacing:.06em;">Dokumen</p>
                            <p style="font-size:13px; font-weight:700; color:#fff;">{{ $docs->count() }} diupload</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step Tracker --}}
        <div class="verif-card reveal rev-d1">
            <div class="verif-card-header">
                <div class="flex items-center gap-sm">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:var(--ux2-primary-soft);">
                        <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">linear_scale</span>
                    </div>
                    <h3 style="font-size:15px; font-weight:700; color:var(--ux2-ink);">Tahapan Verifikasi</h3>
                </div>
            </div>
            <div class="p-md">
                @php
                    $ktpDoc = $docs->first(fn($d) => in_array($d->document_type->value, ['ktp','ktm']));
                    $s1 = $ktpDoc?->status->value;
                    $step1Class = $s1 === 'approved' ? 'done' : ($s1 === 'pending' ? 'pending' : ($s1 === 'rejected' ? 'error' : ''));
                    $step2Class = $s1 ? ($s1 === 'approved' ? 'done' : ($s1 === 'pending' ? 'pending' : '')) : '';
                    $step3Class = $isVerified ? 'done' : '';
                @endphp
                <div class="step-tracker mb-md">
                    @foreach([
                        [$step1Class, 'upload', 'Upload'],
                        [$step2Class, 'manage_search', 'Review'],
                        [$step3Class, 'verified_user', 'Aktif'],
                    ] as $i => [$cls, $icon, $lbl])
                    <div class="step-track-item {{ $cls }}">
                        <div class="step-dot">
                            @if($cls === 'done')
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">check</span>
                            @elseif($cls === 'pending')
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">schedule</span>
                            @elseif($cls === 'error')
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">close</span>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <p style="font-size:10px; font-weight:700; color:var(--ux2-muted); margin-top:6px; text-align:center;">{{ $lbl }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- What's needed --}}
                <div class="p-sm rounded-xl" style="background:var(--ux2-panel);">
                    <p style="font-size:12px; font-weight:700; color:var(--ux2-ink); margin-bottom:8px;">Dokumen yang Diperlukan:</p>
                    <div class="flex items-start gap-2 mb-2">
                        <span class="material-symbols-outlined text-[15px] mt-0.5 flex-shrink-0" style="color:var(--ux2-secondary);">badge</span>
                        <p style="font-size:12px; color:var(--ux2-muted); line-height:1.5;">
                            <strong style="color:var(--ux2-ink);">KTP</strong> (Kartu Tanda Penduduk)
                            <span style="color:var(--ux2-muted);"> atau </span>
                            <strong style="color:var(--ux2-ink);">KTM</strong> (Kartu Tanda Mahasiswa)
                        </p>
                    </div>
                    <p style="font-size:11px; color:var(--ux2-muted); line-height:1.5;">
                        Upload salah satu dokumen identitas yang masih berlaku.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tips Card --}}
        <div class="verif-card reveal rev-d2">
            <div class="verif-card-header">
                <div class="flex items-center gap-sm">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:var(--ux2-accent-soft);">
                        <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-accent); font-variation-settings:'FILL' 1;">tips_and_updates</span>
                    </div>
                    <h3 style="font-size:15px; font-weight:700; color:var(--ux2-ink);">Tips Upload</h3>
                </div>
            </div>
            <div class="p-md flex flex-col gap-xs">
                @foreach([
                    ['photo_camera', 'Foto dari depan, tidak miring'],
                    ['light_mode', 'Cahaya cukup, hindari silau'],
                    ['crop_square', 'Seluruh dokumen terlihat penuh'],
                    ['hd', 'Resolusi minimal cukup jelas dibaca'],
                    ['lock', 'Data aman, hanya admin yang bisa melihat'],
                ] as [$icon, $tip])
                <div class="flex items-center gap-sm p-xs rounded-xl" style="background:var(--ux2-panel);">
                    <span class="material-symbols-outlined text-[16px] flex-shrink-0" style="color:var(--ux2-secondary);">{{ $icon }}</span>
                    <p style="font-size:12px; color:var(--ux2-muted);">{{ $tip }}</p>
                </div>
                @endforeach
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end grid --}}

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* Scroll reveal */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
});

function triggerReupload(docType) {
    const sel = document.getElementById('document_type');
    if (sel) sel.value = docType;
    const sec = document.getElementById('upload-section');
    if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function onFileChosen(input) {
    if (!input.files || !input.files[0]) return;
    const f = input.files[0];
    document.getElementById('chosen-name').textContent = f.name;
    document.getElementById('chosen-size').textContent = (f.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('file-result').classList.add('show');
}

function handleFileDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').classList.remove('over');
    const files = e.dataTransfer.files;
    if (files.length) {
        const input = document.getElementById('file-input');
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        input.files = dt.files;
        onFileChosen(input);
    }
}

function clearFileChoice() {
    document.getElementById('file-input').value = '';
    document.getElementById('file-result').classList.remove('show');
}
</script>
@endsection
