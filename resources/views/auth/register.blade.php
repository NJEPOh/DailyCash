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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full font-sans text-xs md:text-sm lg:text-base antialiased flex flex-col justify-center py-6 lg:py-12 px-3 sm:px-6 lg:px-8 overflow-y-auto">
    <div class="sm:mx-auto sm:w-full sm:max-w-md lg:max-w-lg">
        <div class="flex items-center justify-center gap-3">
            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-[14px] bg-[#059669] border border-[#D1FAE5]/60 flex items-center justify-center text-white font-extrabold text-sm lg:text-lg shadow-md shrink-0">
                Rp
            </div>
            <div>
                <span class="text-[18px] sm:text-xl lg:text-2xl font-bold tracking-tight text-white block leading-none">DailyCash</span>
                <span class="text-[11px] lg:text-sm text-[#D1FAE5] block mt-0.5">Catatan Keuangan Harian Pribadi</span>
            </div>
        </div>

        <div class="mt-5 lg:mt-7 card space-y-4 lg:space-y-5">
            <div class="border-b border-[#F4F5F9] pb-3 lg:pb-4">
                <h1 class="text-[18px] sm:text-xl lg:text-[30px] font-bold text-[#1A1D20] leading-tight">Registrasi Akun Baru</h1>
                <p class="text-[11px] lg:text-sm text-[#7E869E] mt-1">Buat akun pribadi untuk mulai mencatat keuangan dengan 17 kategori default siap pakai.</p>
            </div>

            <x-alert />

            <form action="{{ route('register.store') }}" method="POST" class="space-y-3.5 lg:space-y-4" novalidate>
                @csrf

                <div>
                    <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">
                        Nama Lengkap <span class="text-[#FF6B8B]">*</span>
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
                        class="input-field text-xs md:text-sm lg:text-base {{ $errors->has('name') ? '!border-[#FF6B8B] !bg-[#FF6B8B]/5' : '' }}"
                    >
                    @error('name')
                        <p class="mt-1 text-[11px] lg:text-sm font-medium text-[#FF6B8B]">{{ $message }}</p>
                    @enderror
                </div>

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
                        autocomplete="email"
                        placeholder="nama@email.com"
                        class="input-field text-xs md:text-sm lg:text-base {{ $errors->has('email') ? '!border-[#FF6B8B] !bg-[#FF6B8B]/5' : '' }}"
                    >
                    @error('email')
                        <p class="mt-1 text-[11px] lg:text-sm font-medium text-[#FF6B8B]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 xs-single-col gap-2.5 lg:gap-3.5">
                    <div>
                        <label for="password" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">
                            Password <span class="text-[#FF6B8B]">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Min. 8 karakter"
                            class="input-field text-xs md:text-sm lg:text-base {{ $errors->has('password') ? '!border-[#FF6B8B] !bg-[#FF6B8B]/5' : '' }}"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[#1A1D20] mb-1 lg:mb-1.5">
                            Konfirmasi Password <span class="text-[#FF6B8B]">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="input-field text-xs md:text-sm lg:text-base {{ $errors->has('password') ? '!border-[#FF6B8B] !bg-[#FF6B8B]/5' : '' }}"
                        >
                    </div>
                </div>
                @error('password')
                    <p class="-mt-2 text-[11px] lg:text-sm font-medium text-[#FF6B8B]">{{ $message }}</p>
                @enderror

                <div class="pt-1">
                    <button
                        type="submit"
                        class="btn-primary w-full text-xs lg:text-sm"
                    >
                        <span>Buat Akun Baru</span>
                    </button>
                </div>
            </form>

            <div class="pt-3 lg:pt-4 border-t border-[#D1FAE5] text-center">
                <a href="{{ route('login') }}" class="text-xs lg:text-sm font-semibold text-[#64756B] hover:text-[#059669] hover:underline">
                    Sudah punya akun? Kembali ke halaman login
                </a>
            </div>
        </div>

        <p class="mt-4 lg:mt-5 text-center text-[11px] lg:text-sm text-[#D1FAE5]">
            &copy; {{ date('Y') }} <strong class="text-white">DailyCash</strong> &mdash; Ruang data keuangan baru dimulai dengan saldo Rp0.
        </p>
    </div>
</body>
</html>
