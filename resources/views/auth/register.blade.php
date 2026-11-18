<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Akun — {{ config('app.name', 'DailyCash') }}</title>

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
                            <rect x="18" y="22" width="66" height="82" rx="14" fill="#FFFFFF" fill-opacity="0.1"/>
                            <rect x="30" y="38" width="42" height="10" rx="5" fill="#DDF296"/>
                            <rect x="30" y="56" width="30" height="8" rx="4" fill="#F7C948"/>
                            <rect x="96" y="22" width="66" height="38" rx="14" fill="#E1EFFA"/>
                            <rect x="96" y="68" width="66" height="36" rx="14" fill="#DDF296"/>
                            <circle cx="129" cy="86" r="8" fill="#18191B"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-3">
                    <span class="badge !bg-[#ECF8C2] !text-[#18191B]">17 Kategori Default Siap Pakai</span>
                    <p class="text-[13px] text-white/75 leading-relaxed">
                        Setiap akun baru memulai ruang data kosong dengan saldo Rp0 dan terisolasi penuh demi privasi keuangan Anda.
                    </p>
                </div>
            </aside>

            {{-- Panel Formulir Registrasi --}}
            <section class="lg:col-span-7 bento-card !rounded-[24px] p-5 sm:p-7 lg:p-8 flex flex-col justify-center space-y-5">
                <div class="space-y-1.5">
                    <span class="badge badge-lime">Buat Akun Pribadi</span>
                    <h1 class="bento-h1">
                        Registrasi <span class="bento-h1-accent">Akun Baru</span>
                    </h1>
                    <p class="bento-meta leading-relaxed">
                        Lengkapi data diri Anda untuk mulai mencatat transaksi keuangan harian.
                    </p>
                </div>

                <x-alert />

                <form action="{{ route('register.store') }}" method="POST" class="space-y-4" novalidate>
                    @csrf

                    <div>
                        <label for="name" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                            Nama Lengkap <span class="text-[#C23B3B]">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Contoh: Budi Santoso"
                            class="input-field {{ $errors->has('name') ? '!border-[#C23B3B] !bg-[#FCEAE8]/50' : '' }}"
                        >
                        @error('name')
                            <p class="mt-1 text-[11px] lg:text-[12px] font-semibold text-[#C23B3B]">{{ $message }}</p>
                        @enderror
                    </div>

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
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="input-field {{ $errors->has('email') ? '!border-[#C23B3B] !bg-[#FCEAE8]/50' : '' }}"
                        >
                        @error('email')
                            <p class="mt-1 text-[11px] lg:text-[12px] font-semibold text-[#C23B3B]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 xs-single-col gap-3">
                        <div>
                            <label for="password" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                                Password <span class="text-[#C23B3B]">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Min. 8 karakter"
                                class="input-field {{ $errors->has('password') ? '!border-[#C23B3B] !bg-[#FCEAE8]/50' : '' }}"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[12px] lg:text-[13px] font-semibold text-[#18191B] mb-1.5">
                                Konfirmasi Password <span class="text-[#C23B3B]">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password"
                                class="input-field {{ $errors->has('password') ? '!border-[#C23B3B] !bg-[#FCEAE8]/50' : '' }}"
                            >
                        </div>
                    </div>
                    @error('password')
                        <p class="-mt-2 text-[11px] lg:text-[12px] font-semibold text-[#C23B3B]">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="btn-bento-dark w-full !py-3 text-[13.5px]">
                        <span>Buat Akun Baru</span>
                    </button>
                </form>

                <div class="pt-4 border-t border-[#ECECE9] text-center text-[12px] lg:text-[13px] text-[#8E9299]">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-[#18191B] hover:underline ml-1">
                        Kembali ke halaman login
                    </a>
                </div>
            </section>
        </div>

        <footer class="mt-4 text-center bento-meta">
            &copy; {{ date('Y') }} <strong class="text-[#18191B]">DailyCash</strong> &mdash; Ruang data keuangan baru dimulai dengan saldo Rp0.
        </footer>
    </main>
</body>
</html>
