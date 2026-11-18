<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk Akun — {{ config('app.name', 'DailyCash') }}</title>

    @fonts
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full font-sans antialiased flex flex-col justify-center py-6 px-3 sm:px-6 lg:px-8">
    <main class="w-full max-w-4xl mx-auto">
        <div class="rounded-[24px] lg:rounded-[32px] bg-[#F6F6F4] p-3 sm:p-5 lg:p-6 shadow-[0_20px_50px_rgba(0,0,0,0.04)] grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-5 items-stretch">
            {{-- Panel Bento Visual Kiri --}}
            <aside class="lg:col-span-5 bento-card-dark !rounded-[24px] flex flex-col justify-between gap-6 p-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-[14px] bg-[#DDF296] text-[#18191B] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h11a3 3 0 013 3v1H5a2 2 0 01-2-2zm0 2v8a2 2 0 002 2h13a2 2 0 002-2v-6a2 2 0 00-2-2H5zm13 4h.01"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-[18px] font-bold tracking-tight text-white block leading-none">DailyCash</span>
                            <span class="text-[11.5px] text-white/65 block mt-1">Catatan Keuangan Harian</span>
                        </div>
                    </div>

                    <div class="hidden lg:flex items-center justify-center rounded-[20px] bg-white/5 p-6 my-2">
                        <svg class="w-40 h-32" viewBox="0 0 180 130" fill="none" aria-hidden="true">
                            <rect x="12" y="18" width="94" height="58" rx="14" fill="#FFFFFF" fill-opacity="0.1"/>
                            <rect x="24" y="34" width="38" height="8" rx="4" fill="#DDF296"/>
                            <rect x="24" y="48" width="58" height="8" rx="4" fill="#FFFFFF" fill-opacity="0.3"/>
                            <rect x="78" y="58" width="88" height="54" rx="14" fill="#DDF296"/>
                            <rect x="92" y="72" width="32" height="8" rx="4" fill="#18191B"/>
                            <circle cx="144" cy="86" r="10" fill="#F7C948"/>
                            <circle cx="136" cy="24" r="14" fill="#E1EFFA"/>
                            <path d="M131 24l4 4 7-8" stroke="#18191B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-3">
                    <span class="badge !bg-[#ECF8C2] !text-[#18191B]">Keuangan Pribadi Terisolasi</span>
                    <p class="text-[13px] text-white/75 leading-relaxed">
                        Pantau saldo dompet, kelola pemasukan & pengeluaran, serta evaluasi anggaran bulanan dengan antarmuka bersih dan nyaman.
                    </p>
                </div>
            </aside>

            {{-- Panel Formulir Login --}}
            <section class="lg:col-span-7 bento-card !rounded-[24px] p-5 sm:p-7 lg:p-8 flex flex-col justify-center space-y-5">
                <div class="space-y-1.5">
                    <span class="badge badge-yellow">Akses Akun Pribadi</span>
                    <h1 class="bento-h1">
                        Selamat Datang <span class="bento-h1-accent">Kembali</span>
                    </h1>
                    <p class="bento-meta leading-relaxed">
                        Masuk menggunakan email dan password untuk mengakses ruang keuangan pribadi Anda.
                    </p>
                </div>

                <x-alert />

                <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4" novalidate>
                    @csrf

                    <div>
                        <label for="email" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                            Alamat Email <span class="text-[#C23B3B]">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="input-field {{ $errors->has('email') ? '!border-[#C23B3B] !bg-[#FCEAE8]/50' : '' }}"
                        >
                        @error('email')
                            <p class="mt-1 text-[11px] lg:text-[12px] font-semibold text-[#C23B3B]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                            Password <span class="text-[#C23B3B]">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda"
                            class="input-field {{ $errors->has('password') ? '!border-[#C23B3B] !bg-[#FCEAE8]/50' : '' }}"
                        >
                        @error('password')
                            <p class="mt-1 text-[11px] lg:text-[12px] font-semibold text-[#C23B3B]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <label for="remember" class="inline-flex items-center gap-2 text-[12px] text-[#8E9299] font-medium cursor-pointer select-none">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                value="1"
                                {{ old('remember') ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-[#D5D7DC] accent-[#1E1F21]"
                            >
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-bento-dark w-full !py-3 text-[13.5px]">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 12h14m-6-6 6 6-6 6" />
                        </svg>
                    </button>
                </form>

                <div class="pt-4 border-t border-[#ECECE9] text-center text-[12px] lg:text-[13px] text-[#8E9299]">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-[#18191B] hover:underline ml-1">
                        Registrasi sekarang
                    </a>
                </div>
            </section>
        </div>

        <footer class="mt-4 text-center bento-meta">
            &copy; {{ date('Y') }} <strong class="text-[#18191B]">DailyCash</strong> &mdash; Data keuangan terisolasi dan aman untuk setiap pengguna.
        </footer>
    </main>
</body>
</html>
