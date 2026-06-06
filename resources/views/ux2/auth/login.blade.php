@extends('layouts.ux2.auth')

@section('title', 'Masuk - KosKu')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Mobile Logo Header -->
    <div class="md:hidden text-center mb-lg">
        <h1 class="font-headline-md text-headline-md font-bold text-primary">KosKu</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-md p-sm bg-secondary-container/20 border border-secondary-container rounded-lg">
        <p class="font-body-md text-body-md text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary-container">check_circle</span>
            {{ session('success') }}
        </p>
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-md p-sm bg-error-container/20 border border-error rounded-lg">
        <p class="font-body-md text-body-md text-on-error-container flex items-center gap-2">
            <span class="material-symbols-outlined text-error">error</span>
            {{ $errors->first() }}
        </p>
    </div>
    @endif

    <!-- Form Header -->
    <div class="mb-lg">
        <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-xs">Selamat Datang</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Silakan masuk ke akun Anda untuk melanjutkan.</p>
    </div>
<!-- Social Login -->
<a class="w-full flex items-center justify-center gap-sm py-sm px-md border border-outline-variant rounded-lg hover:bg-surface-container-low transition-colors mb-md font-label-md text-label-md text-on-surface" href="{{ route('ux2.auth.google') }}">
<span class="w-5 h-5 rounded-full border border-outline-variant flex items-center justify-center text-[12px] font-bold">G</span>
                    Lanjutkan dengan Google
                </a>
<div class="flex items-center gap-sm mb-md">
<div class="h-px bg-outline-variant flex-1"></div>
<span class="font-label-sm text-label-sm text-outline">ATAU EMAIL</span>
<div class="h-px bg-outline-variant flex-1"></div>
</div>
<!-- Login Form -->
<form action="{{ route('ux2.login.store') }}" class="flex flex-col gap-md" method="POST">
@csrf
<!-- Email Field -->
<div class="flex flex-col gap-xs">
<label class="font-label-md text-label-md text-on-surface-variant" for="email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">mail</span>
<input class="form-input w-full pl-lg pr-sm py-sm border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md transition-all" id="email" name="email" placeholder="nama@email.com" type="email" value="{{ old('email') }}"/>
</div>
</div>
<!-- Password Field -->
<div class="flex flex-col gap-xs">
<div class="flex justify-between items-center">
<label class="font-label-md text-label-md text-on-surface-variant" for="password">Kata Sandi</label>
<a class="font-label-sm text-label-sm text-secondary hover:text-secondary-fixed transition-colors" href="#">Lupa sandi?</a>
</div>
<div class="relative">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">lock</span>
<input class="form-input w-full pl-lg pr-lg py-sm border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md transition-all" id="password" name="password" placeholder="••••••••" type="password"/>
<button aria-label="Toggle password visibility" class="absolute right-sm top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors" type="button">
<span class="material-symbols-outlined">visibility_off</span>
</button>
</div>
</div>
<!-- Options -->
<div class="flex items-center gap-xs">
<input class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary-container" id="remember" name="remember" type="checkbox" value="1"/>
<label class="font-label-md text-label-md text-on-surface-variant cursor-pointer" for="remember">Ingat saya</label>
</div>
<!-- Submit Button -->
<button class="w-full py-sm px-md bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:bg-inverse-surface transition-colors mt-xs flex justify-center items-center gap-xs" type="submit">
                        Masuk
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</button>
</form>
<!-- Toggle to Sign Up -->
<div class="mt-lg text-center">
<p class="font-body-md text-body-md text-on-surface-variant">
                        Belum punya akun? 
                        <a class="font-label-md text-label-md text-secondary hover:text-secondary-fixed transition-colors font-semibold ml-xs" href="{{ route('ux2.signup') }}">Daftar sekarang</a>
</p>
</div>
</div>
@endsection
