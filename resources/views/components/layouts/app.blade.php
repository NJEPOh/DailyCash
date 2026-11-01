@props([
    'title' => 'DailyCash',
])

<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name', 'DailyCash') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full font-sans text-xs md:text-sm lg:text-base antialiased text-slate-800 bg-slate-50 flex flex-col lg:flex-row overflow-x-hidden">
    <!-- Sidebar / Top Navigation -->
    <aside class="w-full lg:w-64 lg:min-h-screen bg-slate-900 text-slate-100 flex flex-col shrink-0 border-b lg:border-b-0 lg:border-r border-slate-800">
        <div class="px-3.5 py-2.5 lg:px-5 lg:py-4 flex items-center justify-between border-b border-slate-800">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 lg:gap-3 min-w-0">
                <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg lg:rounded-xl bg-emerald-500 flex items-center justify-center text-slate-950 font-bold text-xs lg:text-base shadow-xs shrink-0">
                    Rp
                </div>
                <div class="min-w-0">
                    <span class="text-sm lg:text-lg font-bold tracking-tight text-white block leading-none truncate">DailyCash</span>
                    <span class="text-[11px] lg:text-[13px] text-slate-400 block truncate mt-0.5">Keuangan Harian</span>
                </div>
            </a>

            <div class="flex items-center gap-1.5 lg:hidden">
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-500 text-slate-950 font-semibold text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Catat</span>
                </a>
                <button type="button" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')" class="p-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800" aria-label="Menu Navigasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-nav" class="hidden lg:flex lg:flex-col lg:flex-1 px-3 lg:px-4 py-2.5 lg:py-4 space-y-2 lg:space-y-1.5">
            <div class="hidden lg:block pb-3 mb-2 border-b border-slate-800">
                <a href="{{ route('transactions.create') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold text-xs lg:text-sm transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Catat Transaksi</span>
                </a>
            </div>

            <p class="hidden lg:block px-3 py-1 text-[11px] lg:text-[13px] font-semibold uppercase tracking-wider text-slate-400">Menu Utama</p>

            <!-- Grid 2 Kolom di Mobile, Stack di Desktop -->
            <nav class="grid grid-cols-2 xs-single-col gap-1.5 lg:flex lg:flex-col lg:gap-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2 lg:gap-3 px-2.5 py-2 lg:px-3.5 lg:py-2.5 rounded-lg lg:rounded-xl text-xs lg:text-[15px] font-medium transition {{ request()->routeIs('dashboard*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="truncate">Dashboard</span>
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="flex items-center gap-2 lg:gap-3 px-2.5 py-2 lg:px-3.5 lg:py-2.5 rounded-lg lg:rounded-xl text-xs lg:text-[15px] font-medium transition {{ request()->routeIs('transactions.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="truncate">Transaksi</span>
                </a>

                <a href="{{ route('accounts.index') }}"
                   class="flex items-center gap-2 lg:gap-3 px-2.5 py-2 lg:px-3.5 lg:py-2.5 rounded-lg lg:rounded-xl text-xs lg:text-[15px] font-medium transition {{ request()->routeIs('accounts.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="truncate">Rekening</span>
                </a>

                <a href="{{ route('categories.index') }}"
                   class="flex items-center gap-2 lg:gap-3 px-2.5 py-2 lg:px-3.5 lg:py-2.5 rounded-lg lg:rounded-xl text-xs lg:text-[15px] font-medium transition {{ request()->routeIs('categories.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="truncate">Kategori</span>
                </a>

                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-2 lg:gap-3 px-2.5 py-2 lg:px-3.5 lg:py-2.5 rounded-lg lg:rounded-xl text-xs lg:text-[15px] font-medium transition {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="truncate">Laporan</span>
                </a>
            </nav>

            @auth
                <div class="pt-3 mt-2 border-t border-slate-800 lg:mt-auto space-y-2.5">
                    <div class="px-3 py-2 rounded-lg lg:rounded-xl bg-slate-800/60 border border-slate-800 min-w-0">
                        <p class="text-xs lg:text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] lg:text-[13px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-3 py-2 lg:py-2.5 rounded-lg lg:rounded-xl bg-rose-500/15 hover:bg-rose-500/25 text-rose-300 hover:text-rose-200 border border-rose-500/30 text-xs lg:text-sm font-semibold transition cursor-pointer">
                            <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <div class="hidden lg:block px-5 py-3.5 border-t border-slate-800 text-[13px] text-slate-400">
            <p class="font-semibold text-slate-300">DailyCash v1.0</p>
            <p>{{ now()->translatedFormat('d M Y') }}</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-slate-200 px-3 sm:px-6 lg:px-8 py-2.5 lg:py-3.5 flex items-center justify-between gap-2">
            <div class="text-[11px] lg:text-sm text-slate-500 truncate">
                @auth
                    <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span> &bull;
                @endauth
                <span class="font-medium text-slate-700">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex items-center gap-1.5 lg:gap-2 shrink-0">
                <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 lg:px-3.5 lg:py-1.5 rounded-lg text-xs lg:text-sm font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition">
                    + Pemasukan
                </a>
                <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 lg:px-3.5 lg:py-1.5 rounded-lg text-xs lg:text-sm font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                    - Pengeluaran
                </a>
            </div>
        </header>

        <main class="flex-1 p-3 sm:p-5 lg:p-8 max-w-7xl w-full mx-auto space-y-3.5 sm:space-y-5 lg:space-y-6 min-w-0">
            <x-alert />
            {{ $slot }}
        </main>

        <footer class="px-3 sm:px-6 lg:px-8 py-3 lg:py-4 border-t border-slate-200 bg-white text-[11px] lg:text-sm text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-1.5">
            <span>&copy; {{ date('Y') }} <strong>DailyCash</strong> &mdash; Manajemen Arus Kas Harian</span>
            <span>Format Mata Uang: Rupiah (IDR)</span>
        </footer>
    </div>
</body>
</html>
