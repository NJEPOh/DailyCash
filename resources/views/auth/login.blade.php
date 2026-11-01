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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full font-sans text-xs md:text-sm lg:text-base antialiased flex flex-col justify-center py-6 lg:py-12 px-3 sm:px-6 lg:px-8 overflow-y-auto">
    <div class="sm:mx-auto sm:w-full sm:max-w-md lg:max-w-lg">
        <div class="flex items-center justify-center gap-3">
            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-[14px] bg-[#5D56F3] flex items-center justify-center text-white font-extrabold text-sm lg:text-lg shadow-md shrink-0">
                Rp
            </div>
            <div>
                <span class="text-[18px] sm:text-xl lg:text-2xl font-bold tracking-tight text-[#1A1D20] block leading-none">DailyCash</span>
                <span class="text-[11px] lg:text-sm text-[#7E869E] block mt-0.5">Catatan Keuangan Harian Pribadi</span>
            </div>
        </div>

        <div class="mt-5 lg:mt-7 card space-y-4 lg:space-y-5">
            <div class="border-b border-[#F4F5F9] pb-3 lg:pb-4">
                <h1 class="text-[18px] sm:text-xl lg:text-[30px] font-bold text-[#1A1D20] leading-tight">Masuk ke Akun Anda</h1>
                <p class="text-[11px] lg:text-sm text-[#7E869E] mt-1">Gunakan email dan password untuk mengakses ruang keuangan pribadi Anda.</p>
            </div>

            <x-alert />

            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-3.5 lg:space-y-4" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">
                        Alamat Email <span class="text-[#FF6B8B]">*</span>
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
                        class="input-field text-xs md:text-sm lg:text-base {{ $errors->has('email') ? '!border-[#FF6B8B] !bg-[#FF6B8B]/5' : '' }}"
                    >
                    @error('email')
                        <p class="mt-1 text-[11px] lg:text-sm font-medium text-[#FF6B8B]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">
                        Password <span class="text-[#FF6B8B]">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password Anda"
                        class="input-field text-xs md:text-sm lg:text-base {{ $errors->has('password') ? '!border-[#FF6B8B] !bg-[#FF6B8B]/5' : '' }}"
                    >
                    @error('password')
                        <p class="mt-1 text-[11px] lg:text-sm font-medium text-[#FF6B8B]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-0.5">
                    <label for="remember" class="inline-flex items-center gap-1.5 lg:gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                            class="rounded border-[#A3AABF] text-[#5D56F3] focus:ring-[#5D56F3] w-3.5 h-3.5 lg:w-4 lg:h-4"
                        >
                        <span class="text-[11px] lg:text-sm text-[#7E869E]">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <div class="pt-1">
                    <button
                        type="submit"
                        class="btn-primary w-full text-xs lg:text-sm"
                    >
                        <span>Masuk ke Dashboard</span>
                    </button>
                </div>
            </form>

            <div class="pt-3 lg:pt-4 border-t border-[#F4F5F9] text-center">
                <a href="{{ route('register') }}" class="text-xs lg:text-sm font-semibold text-[#5D56F3] hover:text-[#4B45D3] hover:underline">
                    Belum punya akun? Registrasi
                </a>
            </div>
        </div>

        <p class="mt-4 lg:mt-5 text-center text-[11px] lg:text-sm text-[#7E869E]">
            &copy; {{ date('Y') }} <strong class="text-[#1A1D20]">DailyCash</strong> &mdash; Data keuangan terisolasi per akun pengguna.
        </p>
    </div>
</body>
</html>
