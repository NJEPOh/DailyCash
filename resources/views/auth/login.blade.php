<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk Akun — {{ config('app.name', 'DailyCash') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full font-sans text-xs md:text-sm lg:text-base antialiased text-slate-800 bg-slate-50 flex flex-col justify-center py-6 lg:py-12 px-3 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md lg:max-w-lg">
        <div class="flex items-center justify-center gap-2.5 lg:gap-3.5">
            <div class="w-9 h-9 lg:w-12 lg:h-12 rounded-xl lg:rounded-2xl bg-emerald-500 flex items-center justify-center text-slate-950 font-bold text-sm lg:text-lg shadow-xs shrink-0">
                Rp
            </div>
            <div>
                <span class="text-[18px] sm:text-xl lg:text-2xl font-bold tracking-tight text-slate-900 block leading-none">DailyCash</span>
                <span class="text-[11px] lg:text-sm text-slate-500 block mt-0.5">Catatan Keuangan Harian Pribadi</span>
            </div>
        </div>

        <div class="mt-5 lg:mt-7 bg-white py-5 px-4 sm:px-6 lg:py-7 lg:px-8 shadow-2xs rounded-xl lg:rounded-2xl border border-slate-200 space-y-4 lg:space-y-5">
            <div class="border-b border-slate-100 pb-3 lg:pb-4">
                <h1 class="text-[18px] sm:text-xl lg:text-[30px] font-bold text-slate-900 leading-tight">Masuk ke Akun Anda</h1>
                <p class="text-[11px] lg:text-sm text-slate-500 mt-1">Gunakan email dan password untuk mengakses ruang keuangan pribadi Anda.</p>
            </div>

            <x-alert />

            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-3.5 lg:space-y-4" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-700 mb-1 lg:mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
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
                        class="w-full rounded-lg lg:rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base text-slate-900 focus:border-slate-900 focus:outline-none"
                    >
                    @error('email')
                        <p class="mt-1 text-[11px] lg:text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-slate-700 mb-1 lg:mb-1.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password Anda"
                        class="w-full rounded-lg lg:rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 lg:px-3.5 lg:py-2.5 text-xs md:text-sm lg:text-base text-slate-900 focus:border-slate-900 focus:outline-none"
                    >
                    @error('password')
                        <p class="mt-1 text-[11px] lg:text-sm font-medium text-rose-600">{{ $message }}</p>
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
                            class="rounded border-slate-300 text-slate-900 focus:ring-slate-900 w-3.5 h-3.5 lg:w-4 lg:h-4"
                        >
                        <span class="text-[11px] lg:text-sm text-slate-600">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <div class="pt-1">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-1.5 py-2 lg:py-2.5 px-4 rounded-lg lg:rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs lg:text-sm font-semibold transition shadow-2xs cursor-pointer"
                    >
                        <span>Masuk ke Dashboard</span>
                    </button>
                </div>
            </form>

            <div class="pt-3 lg:pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('register') }}" class="text-xs lg:text-sm font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                    Belum punya akun? Registrasi
                </a>
            </div>
        </div>

        <p class="mt-4 lg:mt-5 text-center text-[11px] lg:text-sm text-slate-400">
            &copy; {{ date('Y') }} <strong>DailyCash</strong> &mdash; Data keuangan terisolasi per akun pengguna.
        </p>
    </div>
</body>
</html>
