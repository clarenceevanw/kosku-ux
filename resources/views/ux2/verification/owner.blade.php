@extends('layouts.ux2.owner')

@section('title', 'Verifikasi Identitas — Owner — KosKu')

@push('styles')
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
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(47,143,121,0.4); }
        70%  { box-shadow: 0 0 0 10px rgba(47,143,121,0); }
        100% { box-shadow: 0 0 0 0 rgba(47,143,121,0); }
    }
    @keyframes progress-fill {
        from { width: 0%; }
    }
    @keyframes float-icon {
        0%,100% { transform: translateY(0) rotate(0deg); }
        33%      { transform: translateY(-6px) rotate(-3deg); }
        66%      { transform: translateY(-3px) rotate(2deg); }
    }
    @keyframes draw-check {
        from { stroke-dashoffset: 60; }
        to   { stroke-dashoffset: 0; }
    }

    .anim-fade-up  { animation: fade-up  0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in  { animation: fade-in  0.4s ease both; }
    .anim-scale-in { animation: scale-in 0.5s cubic-bezier(.22,.68,0,1.2) both; }
    .d1{animation-delay:.06s} .d2{animation-delay:.13s}
    .d3{animation-delay:.20s} .d4{animation-delay:.27s} .d5{animation-delay:.34s}

    /* ── SCROLL REVEAL ───────────────────────── */
    .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1),
                    transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .rev-d1 { transition-delay:.08s }
    .rev-d2 { transition-delay:.18s }
    .rev-d3 { transition-delay:.28s }

    /* ── HERO STATUS CARD ────────────────────── */
    .status-hero {
        border-radius: 16px;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
    }
    .status-hero::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(189,235,216,0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(189,235,216,0.1) 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
    }
    .status-hero-verified  { background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%); }
    .status-hero-pending   { background: linear-gradient(135deg, #b5841a 0%, #7c5a10 100%); }
    .status-hero-incomplete{ background: linear-gradient(135deg, #2a6fd6 0%, #1a4a99 100%); }

    /* ── STEP TIMELINE ───────────────────────── */
    .step-timeline {
        position: relative;
    }
    .step-timeline::before {
        content: '';
        position: absolute;
        left: 22px; top: 0; bottom: 0;
        width: 2px;
        background: var(--ux2-line);
    }
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        position: relative;
        padding-bottom: 24px;
    }
    .step-item:last-child { padding-bottom: 0; }
    .step-icon {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        z-index: 1;
        border: 3px solid var(--ux2-paper);
    }
    .step-icon-done    { background: var(--ux2-secondary); color: #fff; }
    .step-icon-pending { background: #f59e0b; color: #fff; }
    .step-icon-error   { background: var(--ux2-coral); color: #fff; }
    .step-icon-empty   { background: var(--ux2-line); color: var(--ux2-muted); }

    /* ── DOCUMENT CARD ───────────────────────── */
    .doc-card {
        background: #fff;
        border: 1.5px solid var(--ux2-line);
        border-radius: 14px;
        padding: 18px 20px;
        transition: border-color .22s ease, box-shadow .22s ease, transform .22s ease;
        position: relative;
        overflow: hidden;
    }
    .doc-card:hover {
        border-color: var(--ux2-secondary);
        box-shadow: 0 6px 20px rgba(15,42,39,0.1);
        transform: translateY(-2px);
    }
    .doc-card.doc-approved { border-color: var(--ux2-secondary); background: rgba(223,241,236,0.3); }
    .doc-card.doc-pending  { border-color: #f59e0b; background: rgba(245,158,11,0.05); }
    .doc-card.doc-rejected { border-color: var(--ux2-coral); background: rgba(217,95,85,0.04); }
    .doc-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        border-radius: 14px 0 0 14px;
    }
    .doc-card.doc-approved::after { background: var(--ux2-secondary); }
    .doc-card.doc-pending::after  { background: #f59e0b; }
    .doc-card.doc-rejected::after { background: var(--ux2-coral); }

    /* ── UPLOAD ZONE ─────────────────────────── */
    .upload-zone {
        border: 2px dashed var(--ux2-line);
        border-radius: 12px;
        padding: 36px 24px;
        text-align: center;
        transition: border-color .22s ease, background .22s ease;
        cursor: pointer;
        position: relative;
    }
    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: var(--ux2-secondary);
        background: var(--ux2-primary-soft);
    }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0;
        opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .upload-icon { animation: float-icon 4s ease-in-out infinite; }

    /* ── SELECT FIELD ────────────────────────── */
    .doc-select {
        background: #fff;
        border: 1.5px solid var(--ux2-line);
        border-radius: 10px;
        padding: 12px 40px 12px 14px;
        font-size: 14px;
        font-weight: 500;
        color: var(--ux2-ink);
        appearance: none;
        width: 100%;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .doc-select:focus {
        outline: none;
        border-color: var(--ux2-secondary);
        box-shadow: 0 0 0 3px rgba(47,143,121,0.18);
    }

    /* ── BTN SHIMMER ─────────────────────────── */
    .btn-shimmer { position: relative; overflow: hidden; }
    .btn-shimmer::after {
        content:''; position:absolute; top:0; left:-80%;
        width:55%; height:100%;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.22),transparent);
        transform:skewX(-18deg);
        animation: shimmer 2.8s ease-in-out 1s infinite;
    }

    /* ── PROGRESS BAR ────────────────────────── */
    .progress-track {
        height: 8px; border-radius: 4px;
        background: var(--ux2-line);
        overflow: hidden; margin-top: 14px;
    }
    .progress-fill {
        height: 100%; border-radius: 4px;
        background: linear-gradient(90deg, var(--ux2-secondary), var(--ux2-primary));
        animation: progress-fill 1.4s cubic-bezier(.22,.68,0,1) both;
        animation-delay: .4s;
    }

    /* ── TYPE SELECTOR CHIPS ─────────────────── */
    .type-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        border: 1.5px solid var(--ux2-line);
        background: #fff;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: var(--ux2-muted);
        transition: all .2s ease;
    }
    .type-chip:hover { border-color: var(--ux2-secondary); color: var(--ux2-primary); background: var(--ux2-primary-soft); }
    .type-chip.selected {
        border-color: var(--ux2-secondary);
        background: var(--ux2-secondary);
        color: #fff;
    }

    /* ── SECTION CARD ────────────────────────── */
    .section-card {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        box-shadow: var(--ux2-shadow-soft);
        overflow: hidden;
    }
    .section-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--ux2-line);
        display: flex; align-items: center; gap: 12px;
    }
    .section-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ── FILE PREVIEW ────────────────────────── */
    .file-preview {
        display: none;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: var(--ux2-primary-soft);
        border: 1px solid var(--ux2-secondary-soft);
        border-radius: 8px;
        margin-top: 10px;
    }
    .file-preview.visible { display: flex; }
</style>
@endpush

@section('content')

@php
    $docs      = $summary['documents'];
    $isVerified = $summary['is_fully_verified'];
    $hasPending = $summary['has_pending'];
    $pct       = 0;
    if ($docs->count() > 0) {
        $approved = $docs->filter(fn($d) => $d->isApproved())->count();
        $pct = round($approved / max($docs->count(), 1) * 100);
    }
    // Untuk owner: perlu owner_ktp + salah satu dari pbb/electricity_bill/water_bill
    $ownerDocTypes = ['owner_ktp','pbb','electricity_bill','water_bill'];
    $uploadedTypes = $docs->pluck('document_type')->map(fn($t) => $t->value)->toArray();
@endphp

{{-- ════ PAGE HEADER ════ --}}
<div class="mb-lg">
    <div class="flex items-start justify-between gap-md flex-wrap">
        <div>
            <p class="anim-fade-in" style="font-size:11px; font-weight:700; color:var(--ux2-muted); text-transform:uppercase; letter-spacing:.07em; margin-bottom:6px;">Owner · Identitas</p>
            <h1 class="anim-fade-up d1" style="font-size:28px; font-weight:800; color:var(--ux2-ink); line-height:1.2;">Verifikasi Dokumen</h1>
            <p class="anim-fade-up d2" style="font-size:14px; color:var(--ux2-muted); margin-top:4px;">
                Upload & pantau status verifikasi dokumen kepemilikan Anda.
            </p>
        </div>
        {{-- Overall status chip --}}
        <div class="anim-scale-in d3">
            @if($isVerified)
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm"
                    style="background:var(--ux2-secondary); color:#fff;">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">verified</span>
                    Terverifikasi
                </span>
            @elseif($hasPending)
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm"
                    style="background:#fef3c7; color:#92400e; border:1px solid #fbbf24;">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">schedule</span>
                    Menunggu Review
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm"
                    style="background:var(--ux2-primary-soft); color:var(--ux2-primary); border:1px solid var(--ux2-secondary-soft);">
                    <span class="material-symbols-outlined text-[16px]">pending_actions</span>
                    Belum Lengkap
                </span>
            @endif
        </div>
    </div>
</div>

{{-- ════ HERO STATUS BANNER ════ --}}
<div class="anim-fade-up d2 mb-lg">
    <div class="status-hero {{ $isVerified ? 'status-hero-verified' : ($hasPending ? 'status-hero-pending' : 'status-hero-incomplete') }}">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-md">

            {{-- Left: icon + message --}}
            <div class="flex items-center gap-md flex-1">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0"
                    style="background:rgba(255,255,255,0.15); border:2px solid rgba(255,255,255,0.25);">
                    <span class="material-symbols-outlined text-4xl" style="color:#fff; font-variation-settings:'FILL' 1;">
                        {{ $isVerified ? 'verified_user' : ($hasPending ? 'hourglass_top' : 'assignment') }}
                    </span>
                </div>
                <div>
                    <p style="font-size:12px; font-weight:700; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px;">Status Akun Owner</p>
                    <h2 style="font-size:22px; font-weight:800; color:#fff; line-height:1.2;">
                        @if($isVerified) Anda Telah Terverifikasi ✓
                        @elseif($hasPending) Dokumen Sedang Ditinjau
                        @else Lengkapi Dokumen Anda
                        @endif
                    </h2>
                    <p style="font-size:13px; color:rgba(255,255,255,0.7); margin-top:4px;">
                        @if($isVerified) Semua fitur platform tersedia. Kos Anda dapat tampil di pencarian.
                        @elseif($hasPending) Estimasi proses verifikasi: 1×24 jam kerja.
                        @else Upload KTP pemilik & bukti kepemilikan properti kos Anda.
                        @endif
                    </p>
                </div>
            </div>

            {{-- Right: progress ring --}}
            @if(!$isVerified)
            <div class="flex items-center gap-md flex-shrink-0">
                <div class="text-center">
                    <p style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.55); text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px;">Progres</p>
                    <p style="font-size:36px; font-weight:800; color:#fff; line-height:1;">{{ $pct }}%</p>
                    <div class="progress-track" style="width:120px; background:rgba(255,255,255,0.2);">
                        <div class="progress-fill" style="width:{{ $pct }}%; background:rgba(255,255,255,0.85);"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ════ MAIN GRID ════ --}}
<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_380px] gap-lg">

    {{-- ── LEFT: Document Status + Upload Form ── --}}
    <div class="flex flex-col gap-lg">

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="reveal flex items-center gap-3 p-md rounded-xl"
            style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
            <span class="material-symbols-outlined" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">check_circle</span>
            <p style="font-size:14px; color:var(--ux2-primary); font-weight:600;">{{ session('success') }}</p>
        </div>
        @endif
        @if(session('error') || $errors->any())
        <div class="reveal flex flex-col gap-1 p-md rounded-xl"
            style="background:var(--ux2-coral-soft); border:1px solid var(--ux2-coral);">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined" style="color:var(--ux2-coral); font-variation-settings:'FILL' 1;">error</span>
                <p style="font-size:14px; color:var(--ux2-coral); font-weight:600;">
                    {{ session('error') ?? 'Terjadi kesalahan pada form.' }}
                </p>
            </div>
            @if($errors->any())
            <ul style="font-size:13px; color:var(--ux2-coral); margin-top:4px; padding-left:8px;">
                @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
            </ul>
            @endif
        </div>
        @endif

        {{-- ── DOCUMENT STATUS (Timeline Style) ── --}}
        @if($docs->count() > 0)
        <div class="section-card reveal">
            <div class="section-card-header">
                <div class="section-icon" style="background:var(--ux2-primary-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">folder_open</span>
                </div>
                <div>
                    <h3 style="font-size:16px; font-weight:700; color:var(--ux2-ink);">Dokumen Terupload</h3>
                    <p style="font-size:12px; color:var(--ux2-muted);">{{ $docs->count() }} dokumen ditemukan</p>
                </div>
            </div>
            <div class="p-md flex flex-col gap-sm">
                @foreach($docs as $doc)
                @php
                    $st = $doc->status->value;
                    $cardClass = $st === 'approved' ? 'doc-approved' : ($st === 'pending' ? 'doc-pending' : 'doc-rejected');
                    $iconName  = in_array($doc->document_type->value, ['owner_ktp']) ? 'badge' : 'home_work';
                    $stIcon    = $st === 'approved' ? 'check_circle' : ($st === 'pending' ? 'schedule' : 'cancel');
                    $stColor   = $st === 'approved' ? 'var(--ux2-secondary)' : ($st === 'pending' ? '#f59e0b' : 'var(--ux2-coral)');
                    $stLabel   = $st === 'approved' ? 'Disetujui' : ($st === 'pending' ? 'Menunggu' : 'Ditolak');
                @endphp
                <div class="doc-card {{ $cardClass }}">
                    <div class="flex items-start justify-between gap-sm">
                        <div class="flex items-center gap-sm">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                style="background:var(--ux2-primary-soft);">
                                <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">{{ $iconName }}</span>
                            </div>
                            <div>
                                <p style="font-size:14px; font-weight:700; color:var(--ux2-ink);">{{ $doc->document_type->label() }}</p>
                                <p style="font-size:12px; color:var(--ux2-muted);">{{ $doc->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-bold flex-shrink-0"
                            style="font-size:11px; background:rgba(255,255,255,0.7); color:{{ $stColor }}; border:1px solid {{ $stColor }};">
                            <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1;">{{ $stIcon }}</span>
                            {{ $stLabel }}
                        </span>
                    </div>

                    {{-- Admin note if rejected --}}
                    @if($doc->isRejected() && $doc->admin_note)
                    <div class="flex gap-2 mt-sm px-sm py-xs rounded-lg" style="background:var(--ux2-coral-soft); border:1px solid rgba(217,95,85,0.3);">
                        <span class="material-symbols-outlined text-[14px] flex-shrink-0 mt-0.5" style="color:var(--ux2-coral);">info</span>
                        <span style="font-size:12px; color:var(--ux2-coral);"><strong>Alasan ditolak:</strong> {{ $doc->admin_note }}</span>
                    </div>
                    @endif

                    {{-- Reupload button --}}
                    @if(!$doc->isApproved())
                    <div class="mt-sm">
                        <button type="button"
                            onclick="setDocType('{{ $doc->document_type->value }}')"
                            class="inline-flex items-center gap-1 text-xs font-bold transition-colors"
                            style="color:var(--ux2-secondary);">
                            <span class="material-symbols-outlined text-[14px]">upload_file</span>
                            {{ $doc->isRejected() ? 'Upload ulang dokumen ini' : 'Ganti file' }}
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
        <div class="section-card reveal rev-d1" id="upload-section">
            <div class="section-card-header">
                <div class="section-icon" style="background:var(--ux2-accent-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-accent); font-variation-settings:'FILL' 1;">cloud_upload</span>
                </div>
                <div>
                    <h3 style="font-size:16px; font-weight:700; color:var(--ux2-ink);">Upload Dokumen Baru</h3>
                    <p style="font-size:12px; color:var(--ux2-muted);">JPG, PNG, atau PDF · Maks. 5 MB</p>
                </div>
            </div>
            <div class="p-md">
                <form method="POST" action="{{ route('ux2.verification.upload') }}" enctype="multipart/form-data" id="upload-form" class="space-y-md">
                    @csrf

                    {{-- Document type via select --}}
                    <div>
                        <label style="font-size:13px; font-weight:700; color:var(--ux2-ink); display:block; margin-bottom:8px;">
                            Jenis Dokumen <span style="color:var(--ux2-coral);">*</span>
                        </label>
                        <div style="position:relative;">
                            <select name="document_type" id="document_type" required class="doc-select">
                                <option value="">— Pilih jenis dokumen —</option>
                                <option value="owner_ktp" {{ old('document_type') === 'owner_ktp' ? 'selected' : '' }}>KTP Pemilik</option>
                                <option value="pbb" {{ old('document_type') === 'pbb' ? 'selected' : '' }}>Tagihan PBB</option>
                                <option value="electricity_bill" {{ old('document_type') === 'electricity_bill' ? 'selected' : '' }}>Tagihan Listrik</option>
                                <option value="water_bill" {{ old('document_type') === 'water_bill' ? 'selected' : '' }}>Tagihan Air</option>
                            </select>
                            <span class="material-symbols-outlined" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); color:var(--ux2-muted); font-size:18px; pointer-events:none;">expand_more</span>
                        </div>
                    </div>

                    {{-- Drop zone --}}
                    <div>
                        <label style="font-size:13px; font-weight:700; color:var(--ux2-ink); display:block; margin-bottom:8px;">
                            File Dokumen <span style="color:var(--ux2-coral);">*</span>
                        </label>
                        <div class="upload-zone" id="upload-zone"
                            ondragover="event.preventDefault(); this.classList.add('dragover')"
                            ondragleave="this.classList.remove('dragover')"
                            ondrop="handleDrop(event)">
                            <input type="file" name="file" id="file-input"
                                accept=".jpg,.jpeg,.png,.pdf" required
                                onchange="onFileSelect(this)">
                            <div class="upload-icon">
                                <span class="material-symbols-outlined" style="font-size:48px; color:var(--ux2-secondary); display:block; margin-bottom:12px; font-variation-settings:'FILL' 1;">cloud_upload</span>
                            </div>
                            <p style="font-size:15px; font-weight:700; color:var(--ux2-ink); margin-bottom:4px;">
                                Seret file ke sini atau klik untuk memilih
                            </p>
                            <p style="font-size:12px; color:var(--ux2-muted);">JPG, PNG, PDF — Maksimal 5 MB</p>
                        </div>
                        {{-- File preview --}}
                        <div class="file-preview" id="file-preview">
                            <span class="material-symbols-outlined" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1; font-size:20px;">description</span>
                            <div style="flex:1; min-width:0;">
                                <p id="file-name" style="font-size:13px; font-weight:600; color:var(--ux2-ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></p>
                                <p id="file-size" style="font-size:11px; color:var(--ux2-muted);"></p>
                            </div>
                            <button type="button" onclick="clearFile()" style="color:var(--ux2-coral); flex-shrink:0;">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-shimmer w-full flex items-center justify-center gap-2 rounded-xl font-bold py-sm transition-colors"
                        style="background:var(--ux2-primary); color:#fff; font-size:14px;"
                        onmouseover="this.style.background='var(--ux2-primary-deep)'"
                        onmouseout="this.style.background='var(--ux2-primary)'">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">upload</span>
                        Upload Dokumen
                    </button>
                </form>
            </div>
        </div>
        @else
        {{-- Already verified CTA --}}
        <div class="reveal rev-d1 flex flex-col items-center justify-center py-xl px-md text-center rounded-2xl"
            style="background:#fff; border:1px solid var(--ux2-line); box-shadow:var(--ux2-shadow-soft);">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-md"
                style="background:var(--ux2-primary-soft);">
                <span class="material-symbols-outlined text-5xl" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">verified_user</span>
            </div>
            <h3 style="font-size:20px; font-weight:800; color:var(--ux2-ink); margin-bottom:8px;">Akun Anda Sudah Terverifikasi</h3>
            <p style="font-size:14px; color:var(--ux2-muted); max-width:320px; line-height:1.6;">
                Semua dokumen kepemilikan Anda telah diverifikasi oleh admin. Kos Anda aktif di platform.
            </p>
        </div>
        @endif

    </div>{{-- end left --}}

    {{-- ── RIGHT: Requirements + Tips ── --}}
    <div class="flex flex-col gap-lg">

        {{-- Requirement Checklist --}}
        <div class="section-card reveal anim-fade-up d3">
            <div class="section-card-header">
                <div class="section-icon" style="background:var(--ux2-violet-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-violet); font-variation-settings:'FILL' 1;">checklist</span>
                </div>
                <div>
                    <h3 style="font-size:16px; font-weight:700; color:var(--ux2-ink);">Persyaratan Dokumen</h3>
                    <p style="font-size:12px; color:var(--ux2-muted);">Pemilik Kos (Owner)</p>
                </div>
            </div>
            <div class="p-md step-timeline">

                {{-- Step 1: KTP --}}
                @php
                    $ktpDoc = $docs->firstWhere('document_type.value', 'owner_ktp');
                    $s1 = $ktpDoc ? $ktpDoc->status->value : null;
                @endphp
                <div class="step-item">
                    <div class="step-icon {{ $s1 === 'approved' ? 'step-icon-done' : ($s1 === 'pending' ? 'step-icon-pending' : ($s1 === 'rejected' ? 'step-icon-error' : 'step-icon-empty')) }}">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">{{ $s1 === 'approved' ? 'check' : ($s1 === 'rejected' ? 'close' : 'badge') }}</span>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:14px; font-weight:700; color:var(--ux2-ink); margin-bottom:2px;">KTP Pemilik <span style="color:var(--ux2-coral); font-size:11px;">(wajib)</span></p>
                        <p style="font-size:12px; color:var(--ux2-muted); line-height:1.5;">
                            Foto KTP Anda sebagai pemilik kos yang masih berlaku dan terbaca jelas.
                        </p>
                        @if(!$ktpDoc)
                        <button type="button" onclick="setDocType('owner_ktp')"
                            class="mt-2 inline-flex items-center gap-1 text-xs font-bold"
                            style="color:var(--ux2-secondary);">
                            <span class="material-symbols-outlined text-[13px]">upload</span>Upload sekarang
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Step 2: Bukti Kepemilikan --}}
                @php
                    $proofTypes = ['pbb','electricity_bill','water_bill'];
                    $proofDoc = $docs->first(fn($d) => in_array($d->document_type->value, $proofTypes));
                    $s2 = $proofDoc ? $proofDoc->status->value : null;
                @endphp
                <div class="step-item">
                    <div class="step-icon {{ $s2 === 'approved' ? 'step-icon-done' : ($s2 === 'pending' ? 'step-icon-pending' : ($s2 === 'rejected' ? 'step-icon-error' : 'step-icon-empty')) }}">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">{{ $s2 === 'approved' ? 'check' : ($s2 === 'rejected' ? 'close' : 'home_work') }}</span>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:14px; font-weight:700; color:var(--ux2-ink); margin-bottom:2px;">Bukti Kepemilikan <span style="color:var(--ux2-coral); font-size:11px;">(salah satu)</span></p>
                        <p style="font-size:12px; color:var(--ux2-muted); line-height:1.5; margin-bottom:6px;">
                            Tagihan PBB, Listrik, atau Air atas nama Anda sebagai pemilik.
                        </p>
                        <div class="flex flex-wrap gap-1">
                            @foreach([['pbb','Tagihan PBB'],['electricity_bill','Listrik'],['water_bill','Air']] as [$val,$lbl])
                            <button type="button" onclick="setDocType('{{ $val }}')"
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold transition-colors"
                                style="background:var(--ux2-panel); color:var(--ux2-muted); border:1px solid var(--ux2-line);"
                                onmouseover="this.style.background='var(--ux2-secondary)'; this.style.color='#fff'"
                                onmouseout="this.style.background='var(--ux2-panel)'; this.style.color='var(--ux2-muted)'">
                                + {{ $lbl }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info / Tips Card --}}
        <div class="section-card reveal rev-d2">
            <div class="section-card-header">
                <div class="section-icon" style="background:var(--ux2-accent-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-accent); font-variation-settings:'FILL' 1;">tips_and_updates</span>
                </div>
                <h3 style="font-size:16px; font-weight:700; color:var(--ux2-ink);">Tips Upload</h3>
            </div>
            <div class="p-md flex flex-col gap-sm">
                @foreach([
                    ['photo_camera', 'Pastikan foto tidak blur dan semua informasi terbaca jelas.'],
                    ['light_mode', 'Foto di tempat dengan pencahayaan yang cukup, hindari bayangan.'],
                    ['crop_square', 'Pastikan seluruh dokumen terlihat, tidak terpotong di pinggir.'],
                    ['security', 'Dokumen dienkripsi dan hanya dapat diakses oleh admin verifikasi.'],
                ] as [$icon, $tip])
                <div class="flex items-start gap-sm p-sm rounded-xl" style="background:var(--ux2-panel);">
                    <span class="material-symbols-outlined text-[18px] flex-shrink-0 mt-0.5" style="color:var(--ux2-secondary);">{{ $icon }}</span>
                    <p style="font-size:13px; color:var(--ux2-muted); line-height:1.5;">{{ $tip }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- FAQ mini --}}
        <div class="section-card reveal rev-d3">
            <div class="section-card-header">
                <div class="section-icon" style="background:var(--ux2-primary-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">help</span>
                </div>
                <h3 style="font-size:16px; font-weight:700; color:var(--ux2-ink);">FAQ</h3>
            </div>
            <div class="p-md flex flex-col" style="gap:1px; background:var(--ux2-line);">
                @foreach([
                    ['Berapa lama proses verifikasi?', 'Biasanya 1×24 jam kerja setelah dokumen diterima.'],
                    ['Apa yang terjadi jika dokumen ditolak?', 'Anda akan mendapat notifikasi dan bisa upload ulang dengan dokumen yang lebih jelas.'],
                    ['Apakah kos saya langsung aktif?', 'Kos aktif setelah semua dokumen disetujui admin.'],
                ] as [$q, $a])
                <details class="bg-white p-sm cursor-pointer" style="border-radius:0;">
                    <summary class="font-bold" style="font-size:13px; color:var(--ux2-ink); list-style:none; display:flex; justify-content:space-between; align-items:center; gap:8px;">
                        {{ $q }}
                        <span class="material-symbols-outlined text-[18px] flex-shrink-0" style="color:var(--ux2-muted);">expand_more</span>
                    </summary>
                    <p style="font-size:13px; color:var(--ux2-muted); margin-top:8px; line-height:1.6;">{{ $a }}</p>
                </details>
                @endforeach
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end grid --}}

@endsection

@push('scripts')
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

function setDocType(val) {
    const el = document.getElementById('document_type');
    if (el) el.value = val;
    const section = document.getElementById('upload-section');
    if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function onFileSelect(input) {
    if (input.files && input.files[0]) {
        const f = input.files[0];
        document.getElementById('file-name').textContent = f.name;
        document.getElementById('file-size').textContent = (f.size / 1024 / 1024).toFixed(2) + ' MB';
        document.getElementById('file-preview').classList.add('visible');
    }
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length) {
        const input = document.getElementById('file-input');
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        input.files = dt.files;
        onFileSelect(input);
    }
}

function clearFile() {
    document.getElementById('file-input').value = '';
    document.getElementById('file-preview').classList.remove('visible');
}
</script>
@endpush
