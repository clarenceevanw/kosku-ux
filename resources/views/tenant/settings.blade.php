@extends('layouts.tenant')

@section('title', 'Pengaturan')

@section('content')

<div class="max-w-3xl mb-12">
    <h1 class="font-headline text-4xl font-extrabold tracking-tight mb-2 text-primary">Pengaturan Akun</h1>
    <p class="font-body text-lg text-on-surface-variant">Kelola informasi profil dan preferensi notifikasi Anda.</p>
</div>

<div class="bg-surface-container-lowest rounded-[2rem] border border-outline-variant/50 p-8 md:p-12 shadow-sm">
    <form class="flex flex-col gap-8">
        {{-- Profile Section --}}
        <div>
            <h2 class="font-headline text-2xl font-bold text-primary mb-6">Profil Pengguna</h2>
            <div class="flex flex-col md:flex-row gap-6 items-start md:items-center mb-8">
                <div class="w-24 h-24 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center text-3xl font-bold shadow-sm">
                    {{ substr($tenant->name, 0, 1) }}
                </div>
                <div>
                    <button type="button" class="px-6 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface font-label text-sm font-semibold rounded-full transition-colors">
                        Ubah Foto Profil
                    </button>
                    <p class="font-body text-sm text-on-surface-variant mt-2">Format JPG, PNG maks 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-2">
                    <label class="font-label text-sm font-semibold text-on-surface">Nama Lengkap</label>
                    <input type="text" value="{{ $tenant->name }}" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label text-sm font-semibold text-on-surface">Email</label>
                    <input type="email" value="{{ $tenant->email }}" class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all" readonly>
                    <span class="text-xs text-on-surface-variant mt-1">Email digunakan untuk login dan tidak dapat diubah.</span>
                </div>
            </div>
        </div>

        <hr class="border-outline-variant/30">

        {{-- Preferences --}}
        <div>
            <h2 class="font-headline text-2xl font-bold text-primary mb-6">Preferensi</h2>
            
            <div class="flex items-center justify-between p-4 border border-outline-variant/50 rounded-xl mb-4 hover:bg-surface-container-low transition-colors">
                <div>
                    <h3 class="font-label text-sm font-semibold text-on-surface">Notifikasi WhatsApp</h3>
                    <p class="font-body text-sm text-on-surface-variant">Terima pemberitahuan tagihan dan status laporan perbaikan.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between p-4 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                <div>
                    <h3 class="font-label text-sm font-semibold text-on-surface">Notifikasi Email</h3>
                    <p class="font-body text-sm text-on-surface-variant">Terima resi pembayaran dan pengingat via email.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
        </div>

        <div class="pt-6 mt-2 flex justify-end">
            <button type="button" class="bg-primary text-on-primary rounded-xl px-8 py-3.5 font-label text-sm font-semibold tracking-wide hover:bg-inverse-surface transition-colors active:scale-95 shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
