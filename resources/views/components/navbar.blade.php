{{-- Navbar with wired Search form --}}
<nav
    class="fixed top-0 w-full z-50 bg-white/80 dark:bg-[#111827]/80 backdrop-blur-xl shadow-sm border-b border-gray-100">
    <div class="flex justify-between items-center px-6 lg:px-16 h-20 w-full max-w-7xl mx-auto gap-8">
        <div class="flex items-center gap-8">
            <a class="font-display text-2xl font-black text-[#111827] tracking-tighter"
                href="{{ route('home') }}">KosKu</a>

            {{-- Desktop search bar — submits GET /search?q=... --}}
            <form action="{{ route('search') }}" method="GET"
                class="hidden lg:flex items-center bg-gray-50 rounded-full px-4 py-1.5 border border-gray-200 focus-within:border-[#111827] transition-colors w-64 text-xs"
                id="navbar-search-form">
                <button type="submit" class="bg-transparent border-none p-0 cursor-pointer flex items-center">
                    <span class="material-symbols-outlined text-gray-500 text-[18px] mr-2">search</span>
                </button>
                <input id="navbar-search-input"
                    class="bg-transparent border-none outline-none text-xs w-full font-body placeholder-gray-500 text-[#111827] focus:ring-0 p-0"
                    placeholder="Cari lokasi atau nama kos..." type="text" name="q" value="{{ request('q') }}"
                    autocomplete="off">
            </form>
        </div>

        {{-- Center nav links --}}
        <div class="hidden md:flex gap-6 items-center flex-1 justify-center">
            <a class="relative text-sm font-medium pb-1 transition-colors duration-300 after:content-[''] after:absolute after:h-[2px] after:bg-[#111827] after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:transition-all after:duration-300 {{ request()->routeIs('search') ? 'text-[#111827] after:w-full' : 'text-gray-500 hover:text-[#111827] after:w-0 hover:after:w-full' }}"
                href="{{ route('search') }}">Cari Kos</a>
            <a class="relative text-sm font-medium pb-1 transition-colors duration-300 after:content-[''] after:absolute after:h-[2px] after:bg-[#111827] after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:transition-all after:duration-300 {{ request()->routeIs('bot') ? 'text-[#111827] after:w-full' : 'text-gray-500 hover:text-[#111827] after:w-0 hover:after:w-full' }} flex items-center gap-1"
                href="{{ route('bot') }}">
                <span class="material-symbols-outlined text-[16px]">smart_toy</span> Cari via KosBot
            </a>
            @if (!auth()->check() || auth()->user()->role->value === 'owner')
                <a class="relative text-sm font-medium pb-1 transition-colors duration-300 after:content-[''] after:absolute after:h-[2px] after:bg-[#111827] after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:transition-all after:duration-300 {{ request()->routeIs('owner.*') ? 'text-[#111827] after:w-full' : 'text-gray-500 hover:text-[#111827] after:w-0 hover:after:w-full' }}"
                    href="{{ route('owner.dashboard') }}">Untuk Pemilik</a>
            @endif
            @if (!auth()->check() || auth()->user()->role->value === 'tenant')
                <a class="relative text-sm font-medium pb-1 transition-colors duration-300 after:content-[''] after:absolute after:h-[2px] after:bg-[#111827] after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:transition-all after:duration-300 text-gray-500 hover:text-[#111827] after:w-0 hover:after:w-full"
                    href="{{ route('tenant.dashboard') }}">Untuk Penghuni</a>
            @endif
            <a class="relative text-sm font-medium pb-1 transition-colors duration-300 after:content-[''] after:absolute after:h-[2px] after:bg-[#111827] after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:transition-all after:duration-300 text-gray-500 hover:text-[#111827] after:w-0 hover:after:w-full"
                href="#">Bantuan</a>
        </div>

        {{-- Auth buttons --}}
        <div class="flex items-center gap-4">
            @auth
                {{-- Authenticated: show user name + logout --}}
                <span class="hidden md:inline text-sm font-semibold text-gray-600">
                    Halo, {{ Str::words(auth()->user()->name, 1, '') }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-[#111827] font-bold hover:bg-gray-100 rounded-full transition-colors active:scale-95 text-sm">
                        Keluar
                    </button>
                </form>
            @else
                {{-- Guest: Masuk + Daftar --}}
                <a href="{{ route('login') }}"
                    class="hidden md:inline-flex px-4 py-2 text-[#111827] font-bold hover:bg-gray-100 rounded-full transition-colors active:scale-95 text-sm">Sign
                    In</a>
                <a href="{{ route('login') }}?tab=register"
                    class="hidden md:inline-flex px-6 py-2 bg-[#111827] text-white font-bold rounded-full hover:bg-opacity-90 transition-colors active:scale-95 text-sm">Sign
                    Up</a>
            @endauth

            {{-- Hamburger menu button --}}
            <button id="mobile-menu-btn"
                class="md:hidden text-[#111827] focus:outline-none flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu Overlay --}}
<div id="mobile-menu"
    class="fixed inset-0 bg-white z-[60] flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="flex justify-between items-center px-6 h-20 border-b border-gray-100">
        <span class="font-display text-2xl font-black text-[#111827] tracking-tighter">KosKu</span>
        <button id="close-menu-btn"
            class="text-[#111827] focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <div class="flex flex-col px-6 py-8 gap-6 overflow-y-auto">
        <a class="text-lg font-bold text-[#111827]" href="{{ route('search') }}">Cari Kos</a>
        <a class="text-lg font-bold text-[#111827] flex items-center gap-2" href="{{ route('bot') }}">
            <span class="material-symbols-outlined">smart_toy</span> Cari via KosBot
        </a>
        @if (!auth()->check() || auth()->user()->role->value === 'owner')
            <a class="text-lg font-bold {{ request()->routeIs('owner.*') ? 'text-primary' : 'text-[#111827]' }}"
                href="{{ route('owner.dashboard') }}">Untuk Pemilik</a>
        @endif
        @if (!auth()->check() || auth()->user()->role->value === 'tenant')
            <a class="text-lg font-bold text-[#111827]" href="{{ route('tenant.dashboard') }}">Untuk Penghuni</a>
        @endif
        <a class="text-lg font-bold text-[#111827]" href="#">Bantuan</a>

        <hr class="border-gray-100 my-2">

        @auth
            <span class="text-sm font-semibold text-gray-500">Halo, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left text-lg font-bold text-red-600">
                    Keluar
                </button>
            </form>
        @else
            <a href="{{ route('login') }}"
                class="w-full py-3 text-center rounded-full border border-[#111827] text-[#111827] font-bold text-lg">Sign
                In</a>
            <a href="{{ route('login') }}?tab=register"
                class="w-full py-3 text-center rounded-full bg-[#111827] text-white font-bold text-lg">Sign Up</a>
        @endauth
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeMenuBtn = document.getElementById('close-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        function toggleMenu() {
            mobileMenu.classList.toggle('translate-x-full');
            document.body.classList.toggle('overflow-hidden');
        }

        if (mobileMenuBtn && closeMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', toggleMenu);
            closeMenuBtn.addEventListener('click', toggleMenu);
        }
    });
</script>
