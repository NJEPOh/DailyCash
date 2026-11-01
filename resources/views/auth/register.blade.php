<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Akun — {{ config('app.name', 'DailyCash') }}</title>

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
                <h1 class="text-[18px] sm:text-[20px] font-bold text-slate-900 leading-tight">Registrasi Akun Baru</h1>
                <p class="text-[11px] text-slate-500 mt-0.5">Buat akun pribadi untuk mulai mencatat keuangan dari nol dengan ruang data terisolasi.</p>
            </div>

            <x-alert />

            <form action="{{ route('register.store') }}" method="POST" class="space-y-3.5" novalidate>
                @csrf

                <div>
                    <label for="name" class="block text-[11px] font-semibold text-slate-700 mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
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
                        class="w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none"
                    >
                    @error('name')
                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

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
                        autocomplete="email"
                        placeholder="nama@email.com"
                        class="w-full rounded-lg border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none"
                    >
                    @error('email')
                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 xs-single-col gap-2.5">
                    <div>
                        <label for="password" class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Min. 8 karakter"
                            class="w-full rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Konfirmasi Password <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="w-full rounded-lg border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }} px-3 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none"
                        >
                    </div>
                </div>
                @error('password')
                    <p class="-mt-2 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                @enderror

                <div class="pt-1">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-1.5 py-2 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition shadow-2xs cursor-pointer"
                    >
                        <span>Buat Akun Baru</span>
                    </button>
                </div>
            </form>

            <div class="pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 hover:underline">
                    Sudah punya akun? Kembali ke halaman login
                </a>
            </div>
        </div>

        <p class="mt-4 text-center text-[11px] text-slate-400">
            &copy; {{ date('Y') }} <strong>DailyCash</strong> &mdash; Ruang data keuangan baru dimulai dengan saldo Rp0.
        </p>
    </div>
</body>
</html>
