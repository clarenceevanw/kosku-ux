@extends('layouts.ux2.auth')

@section('title', 'Daftar - KosKu')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Mobile Logo Header -->
    <div class="md:hidden text-center mb-lg">
        <h1 class="font-headline-md text-headline-md font-bold text-primary">KosKu</h1>
    </div>

    <!-- Form Header -->
    <div class="mb-lg">
        <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-xs">Buat Akun Baru</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Daftar sekarang dan temukan hunian kos impian Anda.</p>
    </div>

    <!-- Social Login -->
    <a href="{{ route('ux2.auth.google') }}" class="w-full flex items-center justify-center gap-sm py-sm px-md border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors mb-md font-label-md text-label-md text-on-surface">
        <span class="w-5 h-5 rounded-full border border-outline-variant flex items-center justify-center text-[12px] font-bold">G</span>
        Daftar dengan Google
    </a>

    <div class="flex items-center gap-sm mb-md">
        <div class="h-px bg-outline-variant flex-1"></div>
        <span class="font-label-sm text-label-sm text-outline">ATAU EMAIL</span>
        <div class="h-px bg-outline-variant flex-1"></div>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('ux2.register') }}" class="flex flex-col gap-md">
        @csrf

        <!-- Role Selection -->
        <div class="flex flex-col gap-xs">
            <label class="font-label-md text-label-md text-on-surface-variant">Daftar Sebagai</label>
            <div class="grid grid-cols-2 gap-sm">
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="tenant" class="peer sr-only" checked />
                    <div class="p-md border-2 border-outline-variant rounded-lg transition-all peer-checked:border-secondary-container peer-checked:bg-secondary-container/10 hover:border-secondary-container/50">
                        <div class="flex flex-col items-center gap-xs text-center">
                            <span class="material-symbols-outlined text-3xl text-on-surface peer-checked:text-secondary-container">person</span>
                            <span class="font-label-md text-label-md text-on-surface">Penyewa</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Cari kos</span>
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="owner" class="peer sr-only" />
                    <div class="p-md border-2 border-outline-variant rounded-lg transition-all peer-checked:border-secondary-container peer-checked:bg-secondary-container/10 hover:border-secondary-container/50">
                        <div class="flex flex-col items-center gap-xs text-center">
                            <span class="material-symbols-outlined text-3xl text-on-surface peer-checked:text-secondary-container">home_work</span>
                            <span class="font-label-md text-label-md text-on-surface">Pemilik Kos</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Kelola kos</span>
                        </div>
                    </div>
                </label>
            </div>
            @error('role')
                <span class="font-label-sm text-label-sm text-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Name Field -->
        <div class="flex flex-col gap-xs">
            <label for="name" class="font-label-md text-label-md text-on-surface-variant">Nama Lengkap</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">person</span>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    class="form-input w-full pl-lg pr-sm py-sm border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md transition-all"
                    required
                />
            </div>
            @error('name')
                <span class="font-label-sm text-label-sm text-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="flex flex-col gap-xs">
            <label for="email" class="font-label-md text-label-md text-on-surface-variant">Email</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">mail</span>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    class="form-input w-full pl-lg pr-sm py-sm border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md transition-all"
                    required
                />
            </div>
            @error('email')
                <span class="font-label-sm text-label-sm text-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone Field -->
        <div class="flex flex-col gap-xs">
            <label for="phone_number" class="font-label-md text-label-md text-on-surface-variant">Nomor Telepon</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">phone</span>
                <input 
                    type="tel" 
                    id="phone_number" 
                    name="phone_number" 
                    value="{{ old('phone_number') }}"
                    placeholder="08xxxxxxxxxx"
                    class="form-input w-full pl-lg pr-sm py-sm border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md transition-all"
                    required
                />
            </div>
            @error('phone_number')
                <span class="font-label-sm text-label-sm text-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="flex flex-col gap-xs">
            <label for="password" class="font-label-md text-label-md text-on-surface-variant">Kata Sandi</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">lock</span>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Minimal 8 karakter"
                    class="form-input w-full pl-lg pr-lg py-sm border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md transition-all"
                    required
                />
                <button type="button" class="absolute right-sm top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors" aria-label="Toggle password visibility">
                    <span class="material-symbols-outlined">visibility_off</span>
                </button>
            </div>
            @error('password')
                <span class="font-label-sm text-label-sm text-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Confirmation Field -->
        <div class="flex flex-col gap-xs">
            <label for="password_confirmation" class="font-label-md text-label-md text-on-surface-variant">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">lock</span>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Ulangi kata sandi"
                    class="form-input w-full pl-lg pr-lg py-sm border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md transition-all"
                    required
                />
                <button type="button" class="absolute right-sm top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors" aria-label="Toggle password visibility">
                    <span class="material-symbols-outlined">visibility_off</span>
                </button>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="flex items-start gap-xs">
            <input 
                type="checkbox" 
                id="terms" 
                name="terms" 
                class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary-container mt-1"
                required
            />
            <label for="terms" class="font-label-md text-label-md text-on-surface-variant cursor-pointer">
                Saya setuju dengan <a href="#" class="text-secondary hover:text-secondary-fixed">Syarat & Ketentuan</a> dan <a href="#" class="text-secondary hover:text-secondary-fixed">Kebijakan Privasi</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-sm px-md bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:bg-inverse-surface transition-colors mt-xs flex justify-center items-center gap-xs">
            Daftar Sekarang
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
    </form>

    <!-- Toggle to Login -->
    <div class="mt-lg text-center">
        <p class="font-body-md text-body-md text-on-surface-variant">
            Sudah punya akun? 
            <a href="{{ route('ux2.login') }}" class="font-label-md text-label-md text-secondary hover:text-secondary-fixed transition-colors font-semibold ml-xs">Masuk sekarang</a>
        </p>
    </div>
</div>
@endsection
