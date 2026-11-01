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
<body class="min-h-full font-sans text-xs antialiased text-slate-800 bg-slate-50 flex flex-col justify-center py-6 px-3 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex items-center justify-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center text-slate-950 font-bold text-sm shadow-xs shrink-0">
                Rp
            </div>
            <div>
                <span class="text-[18px] sm:text-[20px] font-bold tracking-tight text-slate-900 block leading-none">DailyCash</span>
                <span class="text-[11px] text-slate-500 block mt-0.5">Catatan Keuangan Harian Pribadi</span>
            </div>
        </div>

        <div class="mt-5 bg-white py-5 px-4 sm:px-6 shadow-2xs rounded-xl border border-slate-200 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h1 class="text-[18px] sm:text-[20px] font-bold text-slate-900 leading-tight">Masuk ke Akun Anda</h1>
                <p class="text-[11px] text-slate-500 mt-0.5">Gunakan email dan password untuk mengakses ruang keuangan pribadi Anda.</p>
            </div>

            <x-alert />

            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-3.5" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-[11px] font-semibold text-slate-700 mb-1">
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
                        class="w-full rounded-lg border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none"
                    >
                    @error('email')
                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-semibold text-slate-700 mb-1">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password Anda"
                        class="w-full rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none"
                    >
                    @error('password')
                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-0.5">
                    <label for="remember" class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                            class="rounded border-slate-300 text-slate-900 focus:ring-slate-900 w-3.5 h-3.5"
                        >
                        <span class="text-[11px] text-slate-600">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <div class="pt-1">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-1.5 py-2 px-4 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition shadow-2xs cursor-pointer"
                    >
                        <span>Masuk ke Dashboard</span>
                    </button>
                </div>
            </form>

            <div class="pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('register') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                    Belum punya akun? Registrasi
                </a>
            </div>
        </div>

        <p class="mt-4 text-center text-[11px] text-slate-400">
            &copy; {{ date('Y') }} <strong>DailyCash</strong> &mdash; Data keuangan terisolasi per akun pengguna.
        </p>
    </div>
</body>
</html>
