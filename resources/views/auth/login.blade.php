@extends('layouts.app')

@section('content')

<style>
    .tab-active {
        color: #000000;
        border-bottom: 2px solid #000000;
    }
    .role-active {
        background-color: #111827;
        color: white;
    }
</style>

{{-- ── Main Content Canvas ──────────────────────────────────────── --}}
<main class="flex-grow flex items-center justify-center px-5 pt-28 pb-12 min-h-screen">
    <div class="w-full max-w-[480px]">

        {{-- ── Session / Flash Messages ─────────────────────────────── --}}
        @if(session('success'))
        <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-sm text-green-700 font-medium">
            <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any() && !$errors->has('email') && !$errors->has('password') && !$errors->has('name'))
        <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── Central Card ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transition-all duration-500">

            {{-- Tab Switcher Header --}}
            <div class="flex border-b border-gray-100">
                <button
                    class="flex-1 py-5 text-sm font-bold text-gray-500 hover:text-[#111827] transition-all {{ ($activeTab ?? 'login') === 'login' ? 'tab-active' : '' }}"
                    id="tab-login-trigger"
                    onclick="switchTab('login')"
                    type="button">
                    Sign In
                </button>
                <button
                    class="flex-1 py-5 text-sm font-bold text-gray-500 hover:text-[#111827] transition-all {{ ($activeTab ?? 'login') === 'register' ? 'tab-active' : '' }}"
                    id="tab-register-trigger"
                    onclick="switchTab('register')"
                    type="button">
                    Sign Up
                </button>
            </div>

            {{-- Form Container --}}
            <div class="p-8 md:p-10">

                {{-- ════════════════════════════════
                     TAB 1: LOGIN
                ════════════════════════════════ --}}
                <section class="space-y-6 {{ ($activeTab ?? 'login') === 'login' ? 'block' : 'hidden' }}" id="pane-login">

                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-[#111827] tracking-tight">Selamat Datang Kembali</h2>
                        <p class="text-gray-500 text-sm">Masuk untuk mengelola hunian Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label for="login-email" class="text-sm font-semibold text-gray-500 ml-1">Email</label>
                            <input
                                id="login-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="w-full h-14 px-6 rounded-full bg-gray-50 border {{ $errors->has('email') ? 'border-red-400 focus:ring-red-400' : 'border-transparent focus:ring-teal-500' }} focus:ring-2 transition-all outline-none text-sm">
                            @error('email')
                                <p class="text-red-500 text-xs ml-4 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1.5">
                            <label for="login-pass" class="text-sm font-semibold text-gray-500 ml-1">Password</label>
                            <div class="relative">
                                <input
                                    id="login-pass"
                                    type="password"
                                    name="password"
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="w-full h-14 px-6 rounded-full bg-gray-50 border {{ $errors->has('password') ? 'border-red-400 focus:ring-red-400' : 'border-transparent focus:ring-teal-500' }} focus:ring-2 transition-all outline-none text-sm pr-14">
                                <button class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700" onclick="togglePass('login-pass')" type="button">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs ml-4 mt-1">{{ $message }}</p>
                            @enderror
                            <div class="text-right">
                                <a class="text-xs text-teal-600 font-bold hover:underline" href="#">Lupa Password?</a>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full h-14 bg-[#111827] text-white rounded-full font-bold text-sm hover:opacity-90 active:scale-[0.98] transition-all shadow-lg mt-2">
                            Masuk
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="relative flex items-center py-1">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink mx-4 text-xs text-gray-400 font-bold uppercase tracking-wider">atau</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    {{-- Google Login — ONLY on Login tab --}}
                    <a href="{{ route('auth.google') }}"
                       class="w-full h-14 border border-gray-200 flex items-center justify-center gap-3 rounded-full font-bold text-sm text-gray-700 hover:bg-gray-50 transition-all active:scale-[0.98] shadow-sm">
                        <img alt="Google Logo" class="w-5 h-5"
                             src="https://www.svgrepo.com/show/475656/google-color.svg">
                        Masuk dengan Google
                    </a>

                </section>

                {{-- ════════════════════════════════
                     TAB 2: REGISTER
                ════════════════════════════════ --}}
                <section class="space-y-6 {{ ($activeTab ?? 'login') === 'register' ? 'block' : 'hidden' }}" id="pane-register">

                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-[#111827] tracking-tight">Buat Akun Baru</h2>
                        <p class="text-gray-500 text-sm">Mulai pengalaman sewa hunian premium.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4" id="register-form">
                        @csrf

                        {{-- Hidden role input — driven by the toggle buttons --}}
                        <input type="hidden" name="role" id="role-input" value="{{ old('role', 'tenant') }}">

                        {{-- Role Selection Toggle --}}
                        <div class="bg-gray-100 p-1 rounded-full flex">
                            <button
                                class="flex-1 py-2.5 rounded-full text-sm font-bold transition-all {{ old('role', 'tenant') === 'tenant' ? 'role-active' : '' }}"
                                id="role-tenant"
                                onclick="switchRole('tenant')"
                                type="button">
                                Saya Penyewa
                            </button>
                            <button
                                class="flex-1 py-2.5 rounded-full text-sm font-bold transition-all {{ old('role', 'tenant') === 'owner' ? 'role-active' : '' }}"
                                id="role-owner"
                                onclick="switchRole('owner')"
                                type="button">
                                Saya Pemilik Kos
                            </button>
                        </div>
                        @error('role')
                            <p class="text-red-500 text-xs ml-4">{{ $message }}</p>
                        @enderror

                        {{-- Name --}}
                        <div class="space-y-1.5">
                            <label for="register-name" class="text-sm font-semibold text-gray-500 ml-1">Nama Lengkap</label>
                            <input
                                id="register-name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                autocomplete="name"
                                placeholder="John Doe"
                                class="w-full h-14 px-6 rounded-full bg-gray-50 border {{ $errors->has('name') ? 'border-red-400 focus:ring-red-400' : 'border-transparent focus:ring-teal-500' }} focus:ring-2 transition-all outline-none text-sm">
                            @error('name')
                                <p class="text-red-500 text-xs ml-4 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label for="register-email" class="text-sm font-semibold text-gray-500 ml-1">Email</label>
                            <input
                                id="register-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="w-full h-14 px-6 rounded-full bg-gray-50 border {{ $errors->has('email') ? 'border-red-400 focus:ring-red-400' : 'border-transparent focus:ring-teal-500' }} focus:ring-2 transition-all outline-none text-sm">
                            @error('email')
                                <p class="text-red-500 text-xs ml-4 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="space-y-1.5">
                            <label for="register-phone" class="text-sm font-semibold text-gray-500 ml-1">Nomor Telepon</label>
                            <input
                                id="register-phone"
                                type="tel"
                                name="phone_number"
                                value="{{ old('phone_number') }}"
                                autocomplete="tel"
                                placeholder="+62 812..."
                                class="w-full h-14 px-6 rounded-full bg-gray-50 border {{ $errors->has('phone_number') ? 'border-red-400 focus:ring-red-400' : 'border-transparent focus:ring-teal-500' }} focus:ring-2 transition-all outline-none text-sm">
                            @error('phone_number')
                                <p class="text-red-500 text-xs ml-4 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1.5">
                            <label for="register-pass" class="text-sm font-semibold text-gray-500 ml-1">Password</label>
                            <div class="relative">
                                <input
                                    id="register-pass"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    placeholder="Min. 8 Karakter"
                                    class="w-full h-14 px-6 rounded-full bg-gray-50 border {{ $errors->has('password') ? 'border-red-400 focus:ring-red-400' : 'border-transparent focus:ring-teal-500' }} focus:ring-2 transition-all outline-none text-sm pr-14">
                                <button class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700" onclick="togglePass('register-pass')" type="button">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs ml-4 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="space-y-1.5">
                            <label for="register-pass-confirm" class="text-sm font-semibold text-gray-500 ml-1">Konfirmasi Password</label>
                            <div class="relative">
                                <input
                                    id="register-pass-confirm"
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    placeholder="Ulangi password"
                                    class="w-full h-14 px-6 rounded-full bg-gray-50 border border-transparent focus:ring-2 focus:ring-teal-500 transition-all outline-none text-sm pr-14">
                                <button class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700" onclick="togglePass('register-pass-confirm')" type="button">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full h-14 bg-[#111827] text-white rounded-full font-bold text-sm hover:opacity-90 active:scale-[0.98] transition-all shadow-lg mt-2">
                            Daftar Akun
                        </button>

                    </form>

                    {{-- NOTE: Google button intentionally NOT included on the Register tab per requirements --}}

                </section>

            </div>
        </div>

        {{-- Legal footer text --}}
        <p class="text-center mt-8 text-gray-400 text-xs px-4">
            Dengan melanjutkan, Anda menyetujui
            <a class="text-[#111827] font-bold hover:underline" href="#">Ketentuan Layanan</a>
            dan
            <a class="text-[#111827] font-bold hover:underline" href="#">Kebijakan Privasi</a>
            KosKu.
        </p>

    </div>
</main>

<script>
    // ── Tab Switcher ──────────────────────────────────────────────────────
    function switchTab(tab) {
        const loginPane   = document.getElementById('pane-login');
        const registerPane = document.getElementById('pane-register');
        const loginBtn    = document.getElementById('tab-login-trigger');
        const registerBtn = document.getElementById('tab-register-trigger');

        if (tab === 'login') {
            loginPane.classList.remove('hidden');
            loginPane.classList.add('block');
            registerPane.classList.add('hidden');
            registerPane.classList.remove('block');
            loginBtn.classList.add('tab-active');
            registerBtn.classList.remove('tab-active');
        } else {
            registerPane.classList.remove('hidden');
            registerPane.classList.add('block');
            loginPane.classList.add('hidden');
            loginPane.classList.remove('block');
            registerBtn.classList.add('tab-active');
            loginBtn.classList.remove('tab-active');
        }
    }

    // ── Role Toggle ───────────────────────────────────────────────────────
    function switchRole(role) {
        const tenantBtn = document.getElementById('role-tenant');
        const ownerBtn  = document.getElementById('role-owner');
        const roleInput = document.getElementById('role-input');

        roleInput.value = role;

        if (role === 'tenant') {
            tenantBtn.classList.add('role-active');
            ownerBtn.classList.remove('role-active');
        } else {
            ownerBtn.classList.add('role-active');
            tenantBtn.classList.remove('role-active');
        }
    }

    // ── Password Visibility Toggle ────────────────────────────────────────
    function togglePass(inputId) {
        const input = document.getElementById(inputId);
        const icon  = input.nextElementSibling.querySelector('.material-symbols-outlined');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerText = 'visibility_off';
        } else {
            input.type = 'password';
            icon.innerText = 'visibility';
        }
    }

    // ── Auto-switch to Register tab if there were register errors or ?tab= query ─────────
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'register') {
            switchTab('register');
        } else if (urlParams.get('tab') === 'login') {
            switchTab('login');
        }

        @if(session('_old_input') || (old('name') || old('phone_number')))
            switchTab('register');
        @endif

        @php
            // Detect if errors came from register form (name/phone_number fields are register-only)
            $isRegisterError = $errors->has('name') || $errors->has('phone_number') || $errors->has('role');
            $isLoginError    = !$isRegisterError && ($errors->has('email') || $errors->has('password'));
        @endphp

        @if($isRegisterError)
            switchTab('register');
        @elseif($isLoginError)
            switchTab('login');
        @endif
    });
</script>

@endsection
