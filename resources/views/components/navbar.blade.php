{{-- Navbar with wired Search form --}}
<nav
    class="fixed top-0 w-full z-50 bg-white/80 dark:bg-[#111827]/80 backdrop-blur-xl shadow-sm border-b border-gray-100">
    <div class="flex justify-between items-center px-6 lg:px-16 h-20 w-full max-w-7xl mx-auto gap-8">
        <div class="flex items-center gap-8">
            <a class="font-display text-2xl font-black text-[#111827] tracking-tighter"
                href="{{ route('home') }}">KosKu</a>

            {{-- Desktop search bar with location autocomplete --}}
            <div class="hidden lg:block relative w-72" id="navbar-search-wrapper">
                <form action="{{ route('search') }}" method="GET"
                      class="flex items-center bg-gray-50 rounded-full px-4 py-1.5 border border-gray-200
                             focus-within:border-[#111827] focus-within:bg-white transition-all duration-200"
                      id="navbar-search-form">
                    <button type="submit" class="bg-transparent border-none p-0 cursor-pointer flex items-center shrink-0">
                        <span class="material-symbols-outlined text-gray-500 text-[18px] mr-2">search</span>
                    </button>
                    <input id="navbar-search-input"
                           name="q"
                           type="text"
                           value="{{ request('q') }}"
                           placeholder="Cari kampus, stasiun, area..."
                           autocomplete="off"
                           spellcheck="false"
                           class="bg-transparent border-none outline-none text-xs w-full font-body
                                  placeholder-gray-400 text-[#111827] focus:ring-0 p-0">
                    {{-- Hidden fields — filled by JS when a suggestion is clicked --}}
                    <input type="hidden" name="landmark_id" id="navbar-landmark-id">
                    <input type="hidden" name="district_id" id="navbar-district-id">
                </form>

                {{-- Autocomplete dropdown --}}
                <div id="navbar-search-dropdown"
                     class="absolute top-[calc(100%+8px)] left-0 right-0 bg-white rounded-2xl shadow-xl
                            border border-gray-100 overflow-hidden z-[200] hidden"
                     role="listbox" aria-label="Saran pencarian lokasi">

                    {{-- Loading skeleton --}}
                    <div id="navbar-search-loading" class="hidden px-4 py-3">
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Mencari lokasi...
                        </div>
                    </div>

                    {{-- Results container --}}
                    <div id="navbar-search-results"></div>

                    {{-- No results --}}
                    <div id="navbar-search-empty" class="hidden px-4 py-4 text-center">
                        <span class="material-symbols-outlined text-2xl text-gray-300 block mb-1">search_off</span>
                        <p class="text-xs text-gray-400">Lokasi tidak ditemukan.</p>
                        <p class="text-[11px] text-gray-300 mt-0.5">Coba kata kunci lain atau tekan Enter untuk cari nama kos.</p>
                    </div>
                </div>
            </div>
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
        </div>

        {{-- Auth buttons --}}
        <div class="flex items-center gap-4">
            @auth
                {{-- Authenticated: show user name + logout --}}
                {{-- Verification nudge for unverified users --}}
                @if(!auth()->user()->is_verified)
                <a href="{{ route('verification.index') }}"
                   class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                          border border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    Verifikasi Akun
                </a>
                @endif
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

        <hr class="border-gray-100 my-2">

        @auth
            <span class="text-sm font-semibold text-gray-500">Halo, {{ auth()->user()->name }}</span>
            @if(!auth()->user()->is_verified)
            <a href="{{ route('verification.index') }}"
               class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800">
                <span class="relative flex h-2.5 w-2.5 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                </span>
                <span class="font-bold text-sm">Verifikasi Akun</span>
                <span class="ml-auto text-[11px] font-bold px-2 py-0.5 rounded-full bg-amber-200 text-amber-800">Belum</span>
            </a>
            @endif
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
        /* ─── Mobile hamburger ─────────────────────────────── */
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeMenuBtn  = document.getElementById('close-menu-btn');
        const mobileMenu    = document.getElementById('mobile-menu');

        function toggleMenu() {
            mobileMenu.classList.toggle('translate-x-full');
            document.body.classList.toggle('overflow-hidden');
        }
        if (mobileMenuBtn && closeMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', toggleMenu);
            closeMenuBtn.addEventListener('click', toggleMenu);
        }

        /* ─── Autocomplete Search ──────────────────────────── */
        const input      = document.getElementById('navbar-search-input');
        const dropdown   = document.getElementById('navbar-search-dropdown');
        const results    = document.getElementById('navbar-search-results');
        const loading    = document.getElementById('navbar-search-loading');
        const empty      = document.getElementById('navbar-search-empty');
        const hiddenLmk  = document.getElementById('navbar-landmark-id');
        const hiddenDist = document.getElementById('navbar-district-id');
        const form       = document.getElementById('navbar-search-form');

        if (!input || !dropdown) return;

        const API_URL    = '{{ route('api.location.suggest') }}';
        const SEARCH_URL = '{{ route('search') }}';
        const DEBOUNCE   = 280; // ms
        let debounceTimer, abortCtrl;
        let activeIndex  = -1;
        let allItems     = [];

        /* ── helpers ── */
        function showDropdown() { dropdown.classList.remove('hidden'); }
        function hideDropdown() {
            dropdown.classList.add('hidden');
            activeIndex = -1;
            allItems    = [];
        }
        function clearHidden() {
            hiddenLmk.value  = '';
            hiddenDist.value = '';
        }
        function showLoading() {
            loading.classList.remove('hidden');
            empty.classList.add('hidden');
            results.innerHTML = '';
        }
        function showEmpty() {
            loading.classList.add('hidden');
            empty.classList.remove('hidden');
            results.innerHTML = '';
        }
        function hideLoading() { loading.classList.add('hidden'); }

        /* ── Icon map ── */
        const icons = {
            school:       'school',
            train:        'train',
            shopping_bag: 'shopping_bag',
            location_on:  'location_on',
        };

        /* ── Render groups ── */
        function renderGroups(groups) {
            hideLoading();
            results.innerHTML = '';
            allItems = [];

            if (!groups || groups.length === 0) { showEmpty(); return; }

            empty.classList.add('hidden');

            groups.forEach(group => {
                // Group label header
                const header = document.createElement('div');
                header.className = 'flex items-center gap-2 px-4 pt-3 pb-1';
                header.innerHTML = `
                    <span class="material-symbols-outlined text-[14px] text-gray-400">${icons[group.icon] || 'location_on'}</span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">${group.label}</span>`;
                results.appendChild(header);

                // Items
                group.items.forEach(item => {
                    const el = document.createElement('button');
                    el.type = 'button';
                    el.className = `w-full flex items-start gap-3 px-4 py-2.5 text-left
                        hover:bg-gray-50 transition-colors group suggestion-item`;
                    el.dataset.paramKey   = item.param_key;
                    el.dataset.paramValue = item.param_value;
                    el.dataset.name       = item.name;

                    const typeIcon = item.landmark_type === 'campus'  ? 'school'
                                   : item.landmark_type === 'station' ? 'train'
                                   : item.landmark_type === 'mall'    ? 'shopping_bag'
                                   : 'location_on';

                    el.innerHTML = `
                        <span class="material-symbols-outlined text-[16px] text-gray-400 mt-0.5 shrink-0
                                     group-hover:text-[#111827] transition-colors">${typeIcon}</span>
                        <span class="min-w-0">
                            <span class="block text-sm font-semibold text-[#111827] truncate">${item.name}</span>
                            ${item.subtitle ? `<span class="block text-xs text-gray-400 truncate">${item.subtitle}</span>` : ''}
                        </span>
                        <span class="ml-auto shrink-0 flex items-center gap-1 text-[10px] text-gray-300 font-medium uppercase mt-0.5">
                            <span class="material-symbols-outlined text-[12px]">north_west</span>
                        </span>`;

                    el.addEventListener('click', () => selectItem(item));
                    results.appendChild(el);
                    allItems.push({ el, item });
                });
            });

            // Divider before "Tekan Enter untuk cari"
            const hint = document.createElement('div');
            hint.className = 'px-4 py-2.5 border-t border-gray-50 flex items-center gap-2';
            hint.innerHTML = `<span class="text-[11px] text-gray-400">Tekan <kbd class="px-1 py-0.5 bg-gray-100 rounded text-[10px] font-mono">Enter</kbd> untuk cari nama kos</span>`;
            results.appendChild(hint);

            showDropdown();
        }

        /* ── Select / navigate to item ── */
        function selectItem(item) {
            clearHidden();
            input.value = item.name;

            if (item.param_key === 'landmark_id') {
                hiddenLmk.value  = item.param_value;
            } else {
                hiddenDist.value = item.param_value;
            }

            hideDropdown();
            form.submit();
        }

        /* ── Keyboard highlight helper ── */
        function highlightItem(index) {
            allItems.forEach(({ el }, i) => {
                el.classList.toggle('bg-gray-50', i === index);
            });
        }

        /* ── Keyboard navigation ── */
        input.addEventListener('keydown', e => {
            const visible = !dropdown.classList.contains('hidden');
            if (!visible) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = Math.min(activeIndex + 1, allItems.length - 1);
                highlightItem(activeIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                highlightItem(activeIndex);
            } else if (e.key === 'Enter' && activeIndex >= 0) {
                e.preventDefault();
                selectItem(allItems[activeIndex].item);
            } else if (e.key === 'Escape') {
                hideDropdown();
            }
        });

        /* ── Fetch suggestions ── */
        async function fetchSuggestions(q) {
            if (abortCtrl) abortCtrl.abort();
            abortCtrl = new AbortController();

            showLoading();
            showDropdown();

            try {
                const res  = await fetch(`${API_URL}?q=${encodeURIComponent(q)}`, {
                    signal: abortCtrl.signal,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();
                renderGroups(data.suggestions);
            } catch (err) {
                if (err.name !== 'AbortError') { hideDropdown(); }
            }
        }

        /* ── Input listener with debounce ── */
        input.addEventListener('input', () => {
            clearHidden();
            const q = input.value.trim();
            clearTimeout(debounceTimer);

            if (q.length < 2) { hideDropdown(); return; }

            debounceTimer = setTimeout(() => fetchSuggestions(q), DEBOUNCE);
        });

        /* ── Close on outside click ── */
        document.addEventListener('click', e => {
            const wrapper = document.getElementById('navbar-search-wrapper');
            if (wrapper && !wrapper.contains(e.target)) hideDropdown();
        });

        /* ── Re-open on focus if has value ── */
        input.addEventListener('focus', () => {
            if (input.value.trim().length >= 2 && results.innerHTML !== '') {
                showDropdown();
            }
        });
    });
</script>
