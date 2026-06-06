{{-- ══════════════════════════════════════════════════════════════
     Owner Sidebar — Desktop fixed + Mobile slide-over (hamburger)
══════════════════════════════════════════════════════════════ --}}

{{-- ── Mobile: Hamburger button (top-left) ─────────────────────── --}}
<div class="md:hidden fixed top-0 left-0 right-0 z-40
            flex items-center gap-3 px-4 h-14
            bg-surface-container-lowest border-b border-outline-variant/50 shadow-sm">
    <button id="sidebar-open-btn"
            class="w-9 h-9 flex items-center justify-center rounded-xl
                   hover:bg-surface-container transition-colors">
        <span class="material-symbols-outlined text-on-surface text-[22px]">menu</span>
    </button>
    <a href="{{ route('home') }}"
       class="font-headline text-lg font-bold text-primary">KosKu</a>
    {{-- Verification dot on topbar when not verified --}}
    @if(!auth()->user()->is_verified)
    <a href="{{ route('verification.index') }}"
       class="ml-auto flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold
              bg-amber-50 border border-amber-200 text-amber-700">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
        </span>
        Verifikasi
    </a>
    @else
    <div class="ml-auto flex items-center gap-1 text-[11px] font-bold text-green-700">
        <span class="material-symbols-outlined text-green-600 text-[14px]" style="font-variation-settings:'FILL' 1">verified</span>
        Terverifikasi
    </div>
    @endif
</div>

{{-- ── Mobile: Backdrop overlay ────────────────────────────────── --}}
<div id="sidebar-backdrop"
     class="md:hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300"></div>

{{-- ══════════════════════════════════════════════════════════════
     Sidebar Panel (desktop: always visible | mobile: slide-over)
══════════════════════════════════════════════════════════════ --}}
<nav id="sidebar-panel"
     class="flex flex-col h-screen w-72 fixed left-0 top-0
            bg-surface-container-lowest border-r border-outline-variant/50
            z-[60] overflow-y-auto shadow-[4px_0_24px_rgba(0,0,0,0.05)]
            transition-transform duration-300 ease-in-out
            -translate-x-full md:translate-x-0 md:w-64">

    {{-- ── Panel Header ─────────────────────────────────────────── --}}
    <div class="flex items-center justify-between px-6 pt-6 pb-4">
        <div>
            <a href="{{ route('home') }}"
               class="font-headline text-2xl font-bold text-primary hover:opacity-70 transition-opacity">KosKu</a>
            <div class="mt-1">
                <p class="font-headline text-base font-semibold text-on-surface">Management Hub</p>
                <p class="font-body text-xs text-on-surface-variant">Dashboard Pemilik</p>
            </div>
        </div>
        {{-- Close button (mobile only) --}}
        <button id="sidebar-close-btn"
                class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl
                       hover:bg-surface-container transition-colors shrink-0">
            <span class="material-symbols-outlined text-on-surface-variant text-[20px]">close</span>
        </button>
    </div>

    <div class="px-4 flex flex-col flex-1 gap-2 pb-6">

        {{-- ── Verification Banner ───────────────────────────────── --}}
        @if(!auth()->user()->is_verified)
            @php $hasPending = auth()->user()->identityVerifications()->where('status','pending')->exists(); @endphp
            <a href="{{ route('verification.index') }}"
               class="group flex items-start gap-3 rounded-2xl px-4 py-3.5 mb-1 transition-all duration-200
                      {{ $hasPending
                          ? 'bg-yellow-50 border border-yellow-200 hover:bg-yellow-100'
                          : 'bg-amber-50 border border-amber-200 hover:bg-amber-100' }}">
                {{-- Pulsing dot --}}
                <span class="relative flex h-2.5 w-2.5 shrink-0 mt-[3px]">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $hasPending ? 'bg-yellow-400' : 'bg-amber-500' }}"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $hasPending ? 'bg-yellow-400' : 'bg-amber-500' }}"></span>
                </span>
                <div class="flex-1 min-w-0">
                    @if($hasPending)
                        <p class="text-xs font-bold {{ 'text-yellow-800' }} leading-snug">Menunggu Verifikasi</p>
                        <p class="text-[11px] {{ 'text-yellow-700' }} mt-0.5 leading-snug">Dokumen sedang ditinjau admin</p>
                    @else
                        <p class="text-xs font-bold text-amber-800 leading-snug">Belum Terverifikasi</p>
                        <p class="text-[11px] text-amber-700 mt-0.5 leading-snug">Upload dokumen identitas untuk mendapatkan badge pemilik terpercaya</p>
                    @endif
                </div>
                <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5 group-hover:translate-x-0.5 transition-transform
                             {{ $hasPending ? 'text-yellow-600' : 'text-amber-600' }}">arrow_forward</span>
            </a>
        @else
            <div class="flex items-center gap-2.5 px-4 py-3 mb-1 rounded-2xl bg-green-50 border border-green-200">
                <span class="material-symbols-outlined text-green-600 text-[18px]" style="font-variation-settings:'FILL' 1">verified</span>
                <p class="text-xs font-bold text-green-800">Pemilik Terverifikasi</p>
            </div>
        @endif

        {{-- ── Navigation Items ──────────────────────────────────── --}}
        <nav class="flex flex-col gap-1">
            <a href="{{ route('owner.dashboard') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('owner.dashboard') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]"
                      @if(request()->routeIs('owner.dashboard')) style="font-variation-settings:'FILL' 1" @endif>dashboard</span>
                <span class="font-semibold">Dashboard</span>
            </a>

            <a href="{{ route('owner.kos.index') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('owner.kos.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]"
                      @if(request()->routeIs('owner.kos.*')) style="font-variation-settings:'FILL' 1" @endif>home_work</span>
                <span class="font-semibold">Manajemen Kos</span>
            </a>

            <a href="{{ route('owner.rooms.index') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('owner.rooms.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]"
                      @if(request()->routeIs('owner.rooms.*')) style="font-variation-settings:'FILL' 1" @endif>bed</span>
                <span class="font-semibold">Manajemen Kamar</span>
            </a>

            <a href="{{ route('owner.tickets.index') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('owner.tickets.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]"
                      @if(request()->routeIs('owner.tickets.*')) style="font-variation-settings:'FILL' 1" @endif>report_problem</span>
                <span class="font-semibold">Laporan Kerusakan</span>
            </a>

            <a href="{{ route('owner.transactions.index') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('owner.transactions.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]"
                      @if(request()->routeIs('owner.transactions.*')) style="font-variation-settings:'FILL' 1" @endif>shopping_cart</span>
                <span class="font-semibold">Pemesanan</span>
            </a>

            <a href="{{ route('owner.keuangan.index') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('owner.keuangan.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]"
                      @if(request()->routeIs('owner.keuangan.*')) style="font-variation-settings:'FILL' 1" @endif>payments</span>
                <span class="font-semibold">Keuangan</span>
            </a>

            {{-- Divider --}}
            <div class="border-t border-outline-variant/40 my-1"></div>

            <a href="{{ route('verification.index') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('verification.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="relative flex items-center">
                    <span class="material-symbols-outlined text-[22px]"
                          @if(request()->routeIs('verification.*')) style="font-variation-settings:'FILL' 1" @endif>verified_user</span>
                    @if(!auth()->user()->is_verified)
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-amber-500 rounded-full border-2 border-surface-container-lowest"></span>
                    @endif
                </span>
                <span class="font-semibold flex-1">Verifikasi Identitas</span>
                @if(!auth()->user()->is_verified)
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">Perlu</span>
                @endif
            </a>
        </nav>

        {{-- ── Bottom: User info + Logout ───────────────────────── --}}
        <div class="mt-auto pt-4 border-t border-outline-variant/50 space-y-2">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center
                            text-on-primary font-bold text-base shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm text-on-surface truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm
                               font-semibold text-on-surface-variant hover:bg-surface-container
                               hover:text-error transition-all duration-200">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Keluar
                </button>
            </form>
        </div>

    </div>{{-- /px-4 --}}
</nav>

{{-- ── JavaScript: hamburger toggle ───────────────────────────── --}}
<script>
(function () {
    const openBtn   = document.getElementById('sidebar-open-btn');
    const closeBtn  = document.getElementById('sidebar-close-btn');
    const panel     = document.getElementById('sidebar-panel');
    const backdrop  = document.getElementById('sidebar-backdrop');

    function openSidebar() {
        panel.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
        requestAnimationFrame(() => backdrop.classList.remove('opacity-0'));
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        panel.classList.add('-translate-x-full');
        backdrop.classList.add('opacity-0');
        setTimeout(() => backdrop.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    }

    openBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    backdrop?.addEventListener('click', closeSidebar);
})();
</script>
