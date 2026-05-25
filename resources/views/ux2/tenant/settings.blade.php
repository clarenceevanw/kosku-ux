@extends('layouts.ux2.tenant')

@section('title', 'Pengaturan Akun - KosKu')

@section('content')
<div class="px-6 py-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="font-headline-md text-headline-md font-bold text-primary mb-2">Pengaturan</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Kelola profil dan preferensi akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar Navigation (Desktop) -->
        <div class="hidden md:block col-span-1">
            <nav class="flex flex-col gap-2 sticky top-8">
                <a href="#profil" class="px-4 py-3 bg-secondary-container text-on-secondary-container rounded-xl font-label-md text-label-md font-bold transition-colors">
                    Profil Saya
                </a>
                <a href="#keamanan" class="px-4 py-3 text-on-surface-variant hover:bg-surface-container rounded-xl font-label-md text-label-md transition-colors">
                    Keamanan
                </a>
                <a href="#notifikasi" class="px-4 py-3 text-on-surface-variant hover:bg-surface-container rounded-xl font-label-md text-label-md transition-colors">
                    Notifikasi
                </a>
            </nav>
        </div>

        <!-- Settings Content -->
        <div class="md:col-span-3 space-y-8">
            
            <!-- Profil Saya -->
            <section id="profil" class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 shadow-sm">
                <h2 class="font-headline-sm text-headline-sm font-semibold text-primary mb-6 border-b border-outline-variant/30 pb-4">Profil Saya</h2>
                
                <div class="flex flex-col md:flex-row gap-8 mb-8 items-center md:items-start">
                    <div class="relative group">
                        <div class="w-24 h-24 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center overflow-hidden">
                            <span class="material-symbols-outlined text-4xl">person</span>
                        </div>
                        <button class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-md hover:bg-inverse-surface transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                        </button>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="font-headline-sm text-headline-sm font-bold text-primary">{{ $tenant->name ?? 'Penghuni' }}</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant mb-3">{{ $tenant->email ?? 'email@example.com' }}</p>
                        <span class="px-3 py-1 bg-secondary-container/50 text-on-secondary-container rounded-full text-xs font-bold uppercase tracking-wider border border-secondary/20">Penghuni Kos</span>
                    </div>
                </div>

                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-primary">Nama Lengkap</label>
                            <input type="text" value="{{ $tenant->name }}" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-primary">No. Handphone / WhatsApp</label>
                            <input type="tel" value="{{ $tenant->phone_number }}" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all" />
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-label-md text-label-md text-primary">Pekerjaan</label>
                            <input type="text" value="{{ $tenant->job ?? '' }}" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all" />
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-label-md text-label-md text-primary">Alamat Asal</label>
                            <textarea rows="3" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all resize-none">{{ $tenant->address ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-xl font-label-md text-label-md font-medium hover:bg-inverse-surface transition-colors shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            <!-- Keamanan -->
            <section id="keamanan" class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 shadow-sm">
                <h2 class="font-headline-sm text-headline-sm font-semibold text-primary mb-6 border-b border-outline-variant/30 pb-4">Keamanan</h2>
                
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-primary">Kata Sandi Saat Ini</label>
                            <input type="password" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="font-label-md text-label-md text-primary">Kata Sandi Baru</label>
                                <input type="password" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="font-label-md text-label-md text-primary">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-xl font-label-md text-label-md font-medium hover:bg-inverse-surface transition-colors shadow-sm">
                            Ubah Kata Sandi
                        </button>
                    </div>
                </form>
            </section>

            <!-- Notifikasi -->
            <section id="notifikasi" class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 shadow-sm">
                <h2 class="font-headline-sm text-headline-sm font-semibold text-primary mb-6 border-b border-outline-variant/30 pb-4">Preferensi Notifikasi</h2>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-label-md text-label-md font-bold text-primary">Pengingat Tagihan</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Terima notifikasi H-3 sebelum jatuh tempo pembayaran.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                        </label>
                    </div>
                    
                    <div class="h-px bg-outline-variant/30 w-full"></div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-label-md text-label-md font-bold text-primary">Update Laporan Kerusakan</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Terima notifikasi saat ada respon pada tiket laporan Anda.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                        </label>
                    </div>

                    <div class="h-px bg-outline-variant/30 w-full"></div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-label-md text-label-md font-bold text-primary">Informasi Kos</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Terima pengumuman atau informasi dari pemilik kos.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                        </label>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
