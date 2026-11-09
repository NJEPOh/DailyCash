@props([
    'title' => 'DailyCash',
])

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <style>
    
    html, body {
        background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 50%, #fae8ff 100%) !important;
        background-attachment: fixed !important;
        min-height: 100vh !important;
    }
</style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name', 'DailyCash') }}</title>

    @fonts
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- TEMA UI/UX: Floating Aura Animations -->
    <style>
        body {
            background-color: #f4f6fb !important; /* Base color agak kebiruan */
        }
        
        /* Bola cahaya animasi 1 (Ungu/Biru) */
        .aura-1 {
            position: fixed; width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(93, 86, 243, 0.15) 0%, rgba(93, 86, 243, 0) 70%);
            top: -150px; left: -100px; z-index: -1;
            animation: float-aura1 12s infinite ease-in-out alternate;
        }
        
        /* Bola cahaya animasi 2 (Cyan/Hijau pastel) */
        .aura-2 {
            position: fixed; width: 700px; height: 700px; border-radius: 50%;
            background: radial-gradient(circle, rgba(46, 212, 122, 0.12) 0%, rgba(46, 212, 122, 0) 70%);
            bottom: -200px; right: -150px; z-index: -1;
            animation: float-aura2 15s infinite ease-in-out alternate-reverse;
        }

        @keyframes float-aura1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 30px) scale(1.1); }
        }
        @keyframes float-aura2 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-40px, -40px) scale(1.15); }
        }

        /* INI KUNCI UTAMANYA: Paksa background bawaan Maul jadi transparan biar animasinya tembus! */
        .dashboard-container, .dashboard-main {
            background-color: transparent !important; 
            background: transparent !important;
        }
    </style>
</head>
<body class="min-h-full font-sans text-xs md:text-sm lg:text-base antialiased overflow-x-hidden relative">
    
    <!-- Animasi Background Aura -->
    <div class="aura-1"></div>
    <div class="aura-2"></div>

    <div class="dashboard-container">
        <!-- Floating Neomorphic-Glassmorphic Sidebar (css.md) -->
        <aside class="sidebar">
            <div class="w-full">
                <div class="flex items-center justify-between lg:pb-4 lg:mb-4 lg:border-b lg:border-white/15">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 lg:gap-3 min-w-0">
                        <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-[12px] bg-white text-[#5D56F3] flex items-center justify-center font-extrabold text-xs lg:text-base shadow-sm shrink-0 hover:scale-105 transition-transform duration-300">
                            Rp
                        </div>
                        <div class="min-w-0">
                            <span class="text-sm lg:text-lg font-bold tracking-tight text-white block leading-none truncate">DailyCash</span>
                            <span class="text-[11px] lg:text-[13px] text-white/75 block truncate mt-0.5">Keuangan Harian</span>
                        </div>
                    </a>

                    <div class="flex items-center gap-1.5 lg:hidden">
                        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-white text-[#5D56F3] font-semibold text-xs shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Catat</span>
                        </a>
                        <button type="button" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')" class="sidebar-icon" aria-label="Menu Navigasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="mobile-nav" class="hidden lg:flex lg:flex-col w-full pt-3 lg:pt-0 space-y-3">
                    <div class="hidden lg:block pb-2">
                        <a href="{{ route('transactions.create') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-white hover:bg-white/95 text-[#5D56F3] font-bold text-xs lg:text-sm transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Catat Transaksi</span>
                        </a>
                    </div>

                    <p class="hidden lg:block px-2 text-[11px] lg:text-[12px] font-semibold uppercase tracking-wider text-white/65">Menu Utama</p>

                    <nav class="sidebar-nav grid grid-cols-2 xs-single-col gap-1.5 lg:flex lg:flex-col lg:gap-1.5">
                        <a href="{{ route('dashboard') }}" class="sidebar-link text-xs lg:text-[15px] {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                            <span class="sidebar-icon {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </span>
                            <span class="truncate">Dashboard</span>
                        </a>

                        <a href="{{ route('transactions.index') }}" class="sidebar-link text-xs lg:text-[15px] {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                            <span class="sidebar-icon {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </span>
                            <span class="truncate">Transaksi</span>
                        </a>

                        <a href="{{ route('accounts.index') }}" class="sidebar-link text-xs lg:text-[15px] {{ request()->routeIs('accounts.*') ? 'active' : '' }}">
                            <span class="sidebar-icon {{ request()->routeIs('accounts.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </span>
                            <span class="truncate">Rekening</span>
                        </a>

                        <a href="{{ route('categories.index') }}" class="sidebar-link text-xs lg:text-[15px] {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <span class="sidebar-icon {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </span>
                            <span class="truncate">Kategori</span>
                        </a>

                        <a href="{{ route('reports.index') }}" class="sidebar-link text-xs lg:text-[15px] {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <span class="sidebar-icon {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            <span class="truncate">Laporan</span>
                        </a>
                    </nav>

                    @auth
                        <div class="pt-3 mt-2 border-t border-white/15 lg:hidden space-y-2">
                            <div class="px-3 py-2 rounded-[12px] bg-white/12 min-w-0">
                                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-white/75 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-full bg-[#FF6B8B] text-white text-xs font-semibold transition cursor-pointer">
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>

            @auth
                <div class="hidden lg:block w-full pt-4 border-t border-white/15 space-y-2.5">
                    <div class="px-3.5 py-2.5 rounded-[12px] bg-white/12 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[12px] text-white/75 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-full bg-white/15 hover:bg-[#FF6B8B] text-white text-sm font-semibold transition cursor-pointer">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        <!-- Main Content Area -->
        <div class="dashboard-main bg-transparent backdrop-blur-sm">
            <header class="card !py-2.5 !px-3.5 sm:!px-5 lg:!py-3.5 lg:!px-6 flex items-center justify-between gap-2 shrink-0 bg-white/80 backdrop-blur-md shadow-sm border-b border-white/50">
                <div class="text-[11px] lg:text-sm text-[#7E869E] truncate">
                    @auth
                        <span class="font-semibold text-[#1A1D20]">{{ auth()->user()->name }}</span> &bull;
                    @endauth
                    <span class="font-medium text-[#7E869E]">{{ now()->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5 lg:gap-2 shrink-0">
                    <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="badge badge-green hover:opacity-85 hover:shadow-md hover:-translate-y-0.5 transition-all">
                        + Pemasukan
                    </a>
                    <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="badge badge-pink hover:opacity-85 hover:shadow-md hover:-translate-y-0.5 transition-all">
                        - Pengeluaran
                    </a>
                </div>
            </header>

            <main class="flex-1 max-w-7xl w-full mx-auto space-y-3.5 sm:space-y-5 lg:space-y-6 min-w-0">
                <x-alert />
                {{ $slot }}
            </main>

            <footer class="card !py-2.5 !px-3.5 sm:!px-5 lg:!py-3.5 lg:!px-6 text-[11px] lg:text-sm text-[#7E869E] flex flex-col sm:flex-row items-center justify-between gap-1.5 shrink-0 bg-white/60 backdrop-blur-md border-t border-white/50 mt-auto">
                <span>&copy; {{ date('Y') }} <strong class="text-[#1A1D20]">DailyCash</strong> &mdash; Manajemen Arus Kas Harian</span>
                <span>Format Mata Uang: Rupiah (IDR)</span>
            </footer>
        </div>
    </div>
</body>
<!-- Skrip Paksa Ubah Background -->
    <!-- Skrip Paksa Ubah Background Total -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ubah background utama jadi gradasi ungu-biru soft yang pekat (menghilangkan putih total)
            document.body.style.setProperty("background", "linear-gradient(135deg, #dbeafe 0%, #ede9fe 50%, #fae8ff 100%)", "important");
            document.body.style.setProperty("background-attachment", "fixed", "important");

            // Bikin semua kartu (card) jadi semi-transparan kaca (glassmorphism) biar background-nya nembus
            const cards = document.querySelectorAll(".card");
            cards.forEach(card => {
                card.style.setProperty("background-color", "rgba(255, 255, 255, 0.7)", "important");
                card.style.setProperty("backdrop-filter", "blur(12px)", "important");
                card.style.setProperty("border", "1px solid rgba(255, 255, 255, 0.6)", "important");
            });
        });
    </script>
</html>
</html>
</html>