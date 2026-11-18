@props([
    'title' => 'DailyCash',
])
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name', 'DailyCash') }}</title>

    @fonts
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full font-sans antialiased">
    <div class="bento-wrapper">
        <!-- Sidebar Kiri Mengambang (css.md 4.2) -->
        <aside class="bento-sidebar" aria-label="Navigasi Utama">
            {{-- 1. Logo & Identitas Aplikasi --}}
            <div class="flex items-center justify-between gap-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                    <div class="w-10 h-10 rounded-[14px] bg-[#1E1F21] text-[#DDF296] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h11a3 3 0 013 3v1H5a2 2 0 01-2-2zm0 2v8a2 2 0 002 2h13a2 2 0 002-2v-6a2 2 0 00-2-2H5zm13 4h.01"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[15px] lg:text-[17px] font-bold tracking-tight text-[#18191B] block leading-none truncate">DailyCash</span>
                        <span class="bento-meta block truncate mt-0.5">Keuangan Harian</span>
                    </div>
                </a>

                {{-- Kontrol Mobile --}}
                <div class="flex items-center gap-1.5 lg:hidden">
                    <a href="{{ route('transactions.create') }}" class="btn-lime !py-1.5 !px-3 text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Catat</span>
                    </a>
                    <button
                        type="button"
                        onclick="document.getElementById('mobile-nav').classList.toggle('hidden')"
                        class="w-9 h-9 rounded-[12px] bg-[#F9F9F8] text-[#18191B] flex items-center justify-center cursor-pointer"
                        aria-label="Menu Navigasi"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="mobile-nav" class="hidden lg:flex lg:flex-col lg:flex-1 mt-4 lg:mt-6 gap-5">
                {{-- 2. Ringkasan Profil Pengguna (css.md 4.2) --}}
                @auth
                    @php
                        $initials = collect(explode(' ', trim((string) auth()->user()->name)))
                            ->filter()
                            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
                            ->take(2)
                            ->implode('') ?: 'DC';
                    @endphp
                    <div class="rounded-[18px] bg-[#F9F9F8] p-3 flex items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-[#DDF296] text-[#18191B] font-bold text-xs flex items-center justify-center shrink-0">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-semibold text-[#18191B] truncate leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-[#8E9299] truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                            @csrf
                            <button
                                type="submit"
                                title="Keluar (Logout)"
                                aria-label="Keluar (Logout)"
                                class="w-8 h-8 rounded-[10px] bg-white text-[#8E9299] hover:text-[#C23B3B] hover:bg-[#FCEAE8] flex items-center justify-center transition cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endauth

                {{-- 3. Daftar Navigasi Vertikal (css.md 5.A.1) --}}
                <div>
                    <p class="bento-label px-2 mb-2">Menu Utama</p>
                    <nav class="bento-nav-list grid grid-cols-2 xs-single-col gap-1.5 lg:flex lg:flex-col lg:gap-1">
                        @php
                            $navItems = [
                                [
                                    'route' => 'dashboard',
                                    'active' => request()->routeIs('dashboard*'),
                                    'label' => 'Dashboard',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4 5a1 1 0 011-1h5a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm9 0a1 1 0 011-1h5a1 1 0 011 1v2a1 1 0 01-1 1h-5a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h5a1 1 0 011 1v8a1 1 0 01-1 1h-5a1 1 0 01-1-1v-8zM4 14a1 1 0 011-1h5a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1v-5z"/>',
                                ],
                                [
                                    'route' => 'transactions.index',
                                    'active' => request()->routeIs('transactions.*'),
                                    'label' => 'Transaksi',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                                ],
                                [
                                    'route' => 'accounts.index',
                                    'active' => request()->routeIs('accounts.*'),
                                    'label' => 'Rekening',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                ],
                                [
                                    'route' => 'categories.index',
                                    'active' => request()->routeIs('categories.*'),
                                    'label' => 'Kategori',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',
                                ],
                                [
                                    'route' => 'reports.index',
                                    'active' => request()->routeIs('reports.*'),
                                    'label' => 'Laporan',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                ],
                                [
                                    'route' => 'wireframes.index',
                                    'active' => request()->routeIs('wireframes.*'),
                                    'label' => 'Semua Modul',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>',
                                ],
                            ];
                        @endphp

                        @foreach ($navItems as $item)
                            <a href="{{ route($item['route']) }}" class="bento-nav-link {{ $item['active'] ? 'active' : '' }}">
                                @if ($item['active'])
                                    <span class="bento-nav-indicator" aria-hidden="true"></span>
                                @endif
                                <svg class="w-4 h-4 lg:w-[18px] lg:h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    {!! $item['icon'] !!}
                                </svg>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="lg:hidden pt-1">
                        @csrf
                        <button type="submit" class="btn-danger w-full !rounded-[14px]">
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                @endauth

                {{-- 4. Kartu Bantuan Sidebar (css.md 5.C.4) --}}
                <div class="hidden lg:block mt-auto pt-4">
                    <div class="bento-help-card overflow-visible">
                        <div class="bento-help-badge" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m-7-7h14"/>
                            </svg>
                        </div>

                        <svg class="w-full h-6 opacity-15 mb-1 text-white" viewBox="0 0 180 28" fill="none" aria-hidden="true">
                            <path d="M4 18C26 4 48 4 70 16C92 28 114 4 136 14C152 21 166 12 176 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>

                        <h3 class="text-[14px] font-semibold text-white">Catat Arus Kas</h3>
                        <p class="text-[11.5px] text-white/70 mt-1 leading-relaxed">
                            Simpan pemasukan atau pengeluaran baru secara instan.
                        </p>
                        <a href="{{ route('transactions.create') }}" class="mt-3.5 inline-flex items-center justify-center w-full py-2 px-4 rounded-full bg-white text-[#18191B] text-[12px] font-semibold hover:bg-[#DDF296] transition">
                            + Transaksi Baru
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Area Kerja Utama (css.md 4.3) -->
        <div class="bento-workspace min-w-0">
            {{-- A. Header Utama (css.md 4.3.A + 5.A.2, 5.A.3, 5.A.4) --}}
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-1">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <form action="{{ route('transactions.index') }}" method="GET" class="bento-search-wrap max-w-md flex-1">
                        <svg class="w-4 h-4 text-[#8E9299] absolute left-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari kategori, rekening, atau deskripsi transaksi..."
                            aria-label="Cari transaksi"
                            class="bento-search-input"
                        >
                    </form>
                    <span class="hidden xl:inline-flex badge badge-neutral">
                        {{ now()->translatedFormat('d M Y') }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-between md:justify-end gap-2.5 shrink-0">
                    <div class="bento-switcher" role="group" aria-label="Aksi Cepat Tipe Transaksi">
                        <a
                            href="{{ route('transactions.create', ['type' => 'income']) }}"
                            class="bento-switcher-tab {{ request('type') === 'income' ? 'active' : '' }}"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-[#9AB847]"></span>
                            + Pemasukan
                        </a>
                        <a
                            href="{{ route('transactions.create', ['type' => 'expense']) }}"
                            class="bento-switcher-tab {{ request('type') === 'expense' ? 'active' : '' }}"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-[#F7C948]"></span>
                            - Pengeluaran
                        </a>
                    </div>

                    <a href="{{ route('transactions.create') }}" class="btn-bento-dark hidden sm:inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m-7-7h14"/>
                        </svg>
                        <span>Tambah Transaksi</span>
                    </a>
                </div>
            </header>

            <main class="flex-1 w-full space-y-5 lg:space-y-6 min-w-0">
                <x-alert />
                {{ $slot }}
            </main>

            <footer class="pt-4 mt-auto border-t border-[#ECECE9] flex flex-col sm:flex-row items-center justify-between gap-2 bento-meta">
                <span>&copy; {{ date('Y') }} <strong class="text-[#18191B]">DailyCash</strong> &mdash; Manajemen Keuangan Pribadi</span>
                <span>Mata Uang: Rupiah (IDR)</span>
            </footer>
        </div>
    </div>
</body>
</html>
