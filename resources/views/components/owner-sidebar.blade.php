{{-- ══════════════════════════════════════════════
     Desktop Sidebar Navigation
══════════════════════════════════════════════ --}}
<nav
    class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 bg-surface-container-lowest border-r border-outline-variant/50 p-6 gap-2 z-40 overflow-y-auto shadow-[4px_0_24px_rgba(0,0,0,0.02)]">

    {{-- Brand --}}
    <div class="mb-8 px-4 pt-2">
        <a href="{{ route('home') }}"
            class="font-headline text-2xl font-bold text-primary hover:opacity-70 transition-opacity">KosKu</a>
        <div class="mt-2">
            <p class="font-headline text-lg font-semibold text-on-surface">Management Hub</p>
            <p class="font-body text-sm text-on-surface-variant">Dashboard Pemilik</p>
        </div>
    </div>

    {{-- Navigation Items --}}
    <div class="flex-1 flex flex-col gap-1">
        <a href="{{ route('owner.dashboard') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                  {{ request()->routeIs('owner.dashboard') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]"
                @if (request()->routeIs('owner.dashboard')) style="font-variation-settings:'FILL' 1" @endif>dashboard</span>
            <span class="font-semibold">Dashboard</span>
        </a>

        <a href="{{ route('owner.kos.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                  {{ request()->routeIs('owner.kos.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]"
                @if (request()->routeIs('owner.kos.*')) style="font-variation-settings:'FILL' 1" @endif>home_work</span>
            <span class="font-semibold">Manajemen Kos</span>
        </a>

        <a href="{{ route('owner.rooms.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                  {{ request()->routeIs('owner.rooms.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]"
                @if (request()->routeIs('owner.rooms.*')) style="font-variation-settings:'FILL' 1" @endif>bed</span>
            <span class="font-semibold">Manajemen Kamar</span>
        </a>

        <a href="{{ route('owner.tickets.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                  {{ request()->routeIs('owner.tickets.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]"
                @if (request()->routeIs('owner.tickets.*')) style="font-variation-settings:'FILL' 1" @endif>report_problem</span>
            <span class="font-semibold">Laporan Kerusakan</span>
        </a>

        <a href="{{ route('owner.transactions.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                  {{ request()->routeIs('owner.transactions.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]"
                @if (request()->routeIs('owner.transactions.*')) style="font-variation-settings:'FILL' 1" @endif>shopping_cart</span>
            <span class="font-semibold">Pemesanan</span>
        </a>

        <a href="{{ route('owner.keuangan.index') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                  {{ request()->routeIs('owner.keuangan.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined text-[22px]"
                @if (request()->routeIs('owner.keuangan.*')) style="font-variation-settings:'FILL' 1" @endif>payments</span>
            <span class="font-semibold">Keuangan</span>
        </a>
    </div>

    {{-- Bottom: User info + Logout --}}
    <div class="mt-auto pt-6 border-t border-outline-variant/50 space-y-3">

        {{-- User avatar & name --}}
        <div class="flex items-center gap-3 px-2 py-2">
            <div
                class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary font-bold text-base shrink-0 shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-sm text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-semibold text-on-surface-variant hover:bg-surface-container hover:text-error transition-all duration-200">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Keluar
            </button>
        </form>
    </div>
</nav>

{{-- ══════════════════════════════════════════════
     Mobile Bottom Navigation
══════════════════════════════════════════════ --}}
<nav
    class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center py-2 px-2 bg-surface-container-lowest border-t border-outline-variant/50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.06)] z-50">
    <a href="{{ route('owner.dashboard') }}"
        class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
              {{ request()->routeIs('owner.dashboard') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <span class="material-symbols-outlined"
            @if (request()->routeIs('owner.dashboard')) style="font-variation-settings:'FILL' 1" @endif>dashboard</span>
        <span class="font-label text-[10px] mt-0.5 font-semibold">Home</span>
    </a>
    <a href="{{ route('owner.kos.index') }}"
        class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
              {{ request()->routeIs('owner.kos.*') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <span class="material-symbols-outlined"
            @if (request()->routeIs('owner.kos.*')) style="font-variation-settings:'FILL' 1" @endif>home_work</span>
        <span class="font-label text-[10px] mt-0.5 font-semibold">KosKu</span>
    </a>
    <a href="{{ route('owner.rooms.index') }}"
        class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
              {{ request()->routeIs('owner.rooms.*') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <span class="material-symbols-outlined"
            @if (request()->routeIs('owner.rooms.*')) style="font-variation-settings:'FILL' 1" @endif>bed</span>
        <span class="font-label text-[10px] mt-0.5 font-semibold">Kamar</span>
    </a>
    <a href="{{ route('owner.tickets.index') }}"
        class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
              {{ request()->routeIs('owner.tickets.*') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <span class="material-symbols-outlined"
            @if (request()->routeIs('owner.tickets.*')) style="font-variation-settings:'FILL' 1" @endif>report_problem</span>
        <span class="font-label text-[10px] mt-0.5 font-semibold">Laporan</span>
    </a>
    <a href="{{ route('owner.transactions.index') }}"
        class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
              {{ request()->routeIs('owner.transactions.*') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <span class="material-symbols-outlined"
            @if (request()->routeIs('owner.transactions.*')) style="font-variation-settings:'FILL' 1" @endif>shopping_cart</span>
        <span class="font-label text-[10px] mt-0.5 font-semibold">Pemesanan</span>
    </a>
    <a href="{{ route('owner.keuangan.index') }}"
        class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
              {{ request()->routeIs('owner.keuangan.*') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
        <span class="material-symbols-outlined"
            @if (request()->routeIs('owner.keuangan.*')) style="font-variation-settings:'FILL' 1" @endif>payments</span>
        <span class="font-label text-[10px] mt-0.5 font-semibold">Keuangan</span>
    </a>
</nav>
