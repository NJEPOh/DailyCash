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

    <style>
        :root {
            --login-forest: #064e3b;
            --login-emerald: #087f5b;
            --login-green: #0da875;
            --login-mint: #dff7eb;
            --login-gold: #d6b56d;
            --login-ink: #123f32;
            --login-muted: #527568;
        }

        /* Tidak mengganti background global agar animasi Emerald DailyCash tetap tampil. */
        .login-page {
            min-height: 100vh;
            color: var(--login-ink);
            position: relative;
            isolation: isolate;
        }

        .login-shell {
            width: 100%;
            max-width: 520px;
            margin: auto;
            position: relative;
            z-index: 2;
        }

        .login-brand {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 14px;
            margin: 0 auto 24px;
            padding: 8px 12px;
            width: fit-content;
        }

        .login-logo {
            width: 58px;
            height: 58px;
            flex: 0 0 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 19px;
            color: #f8e8bd;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(145deg, #0b956b 0%, #07543f 100%);
            border: 1px solid rgba(230, 205, 143, 0.85);
            box-shadow: 0 9px 24px rgba(0, 25, 17, 0.25), inset 0 1px 0 rgba(255,255,255,.20);
        }

        .login-brand-name {
            display: block;
            color: #ffffff;
            font-size: 25px;
            line-height: 1.05;
            font-weight: 800;
            letter-spacing: -1px;
            text-shadow: 0 2px 14px rgba(0, 20, 14, .28);
        }

        .login-brand-tagline {
            display: block;
            margin-top: 5px;
            color: #c7f3df;
            font-size: 12px;
            line-height: 1.4;
            font-weight: 600;
            letter-spacing: .15px;
        }

        .login-card {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border-radius: 27px;
            background: rgba(239, 252, 245, .94);
            border: 1px solid rgba(224, 196, 128, .62);
            box-shadow:
                0 26px 70px rgba(0, 25, 17, .28),
                0 5px 18px rgba(0, 30, 20, .10),
                inset 0 1px 0 rgba(255,255,255,.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 30px;
            right: 30px;
            height: 3px;
            border-radius: 0 0 10px 10px;
            background: linear-gradient(90deg, transparent, #c6a45b, #0da875, #c6a45b, transparent);
            opacity: .95;
        }

        .login-kicker {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 12px;
            color: #087f5b;
            font-size: 10px;
            line-height: 1.3;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .login-kicker-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #c6a45b;
            box-shadow: 0 0 0 4px rgba(198,164,91,.15);
        }

        .login-title {
            margin: 0;
            color: #103f31;
            font-size: clamp(25px, 5vw, 31px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -.9px;
        }

        .login-description {
            margin-top: 9px;
            max-width: 420px;
            color: #557568;
            font-size: 13px;
            line-height: 1.65;
        }

        .login-divider {
            height: 1px;
            margin: 20px 0;
            background: linear-gradient(90deg, rgba(8,127,91,.25), rgba(198,164,91,.55), rgba(8,127,91,.18));
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 17px;
        }

        .login-label {
            display: block;
            margin-bottom: 7px;
            color: #164b39;
            font-size: 12px;
            font-weight: 700;
        }

        .login-required {
            color: #e65b68;
        }

        .login-input-wrap {
            position: relative;
        }

        .login-input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #57917a;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .login-input {
            display: block;
            width: 100%;
            min-height: 50px;
            padding: 12px 14px 12px 43px;
            border: 1px solid #b8d9c8;
            border-radius: 14px;
            outline: none;
            background: rgba(255,255,255,.88);
            color: #173f32;
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            box-shadow: inset 0 1px 2px rgba(10,60,40,.035);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease, transform .2s ease;
        }

        .login-input::placeholder {
            color: #8aa79a;
        }

        .login-input:hover {
            border-color: #83bda2;
            background: #ffffff;
        }

        .login-input:focus {
            border-color: #0b9b6c;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(13,168,117,.13), 0 0 0 1px rgba(198,164,91,.25);
        }

        .login-input.is-invalid {
            border-color: #e87983;
            background: #fff8f8;
            box-shadow: 0 0 0 3px rgba(232,121,131,.10);
        }

        .login-error {
            margin-top: 6px;
            color: #c24150;
            font-size: 11px;
            font-weight: 600;
        }

        .login-remember {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #557568;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .login-remember input {
            width: 16px;
            height: 16px;
            accent-color: #087f5b;
            cursor: pointer;
        }

        .login-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 51px;
            padding: 12px 20px;
            border: 1px solid rgba(225, 197, 130, .55);
            border-radius: 999px;
            color: #ffffff;
            background: linear-gradient(110deg, #087f5b 0%, #0da875 55%, #07865f 100%);
            box-shadow: 0 9px 20px rgba(8,127,91,.22), inset 0 1px 0 rgba(255,255,255,.20);
            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }

        .login-submit:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow: 0 13px 25px rgba(8,127,91,.29), inset 0 1px 0 rgba(255,255,255,.24);
        }

        .login-submit:active {
            transform: translateY(0) scale(.99);
        }

        .login-register {
            padding-top: 17px;
            border-top: 1px solid rgba(8,127,91,.16);
            text-align: center;
            color: #638174;
            font-size: 12px;
        }

        .login-register a {
            color: #087f5b;
            font-weight: 800;
            text-decoration: none !important;
            transition: color .2s ease;
        }

        .login-register a:hover {
            color: #a47b2f;
            text-decoration: none !important;
        }

        .login-footer {
            margin-top: 19px;
            color: #d0f2e1;
            font-size: 11px;
            line-height: 1.6;
            text-align: center;
            text-shadow: 0 1px 8px rgba(0,20,14,.25);
        }

        .login-footer strong {
            color: #f3dfac;
            font-weight: 800;
        }

        @media (max-width: 520px) {
            .login-brand {
                margin-bottom: 17px;
            }

            .login-logo {
                width: 51px;
                height: 51px;
                flex-basis: 51px;
                border-radius: 17px;
                font-size: 19px;
            }

            .login-brand-name {
                font-size: 22px;
            }

            .login-card {
                padding: 23px 19px;
                border-radius: 23px;
            }

            .login-card::before {
                left: 20px;
                right: 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-input,
            .login-submit,
            .login-register a {
                transition: none !important;
            }
        }
    </style>
</head>
<body class="login-page min-h-full font-sans antialiased flex flex-col justify-center py-6 lg:py-12 px-3 sm:px-6 lg:px-8 overflow-y-auto">
    <main class="login-shell">
        <!-- Brand DailyCash -->
        <header class="login-brand">
            <div class="login-logo" aria-label="Logo DailyCash">Rp</div>
            <div>
                <span class="login-brand-name">DailyCash</span>
                <span class="login-brand-tagline">Catatan Keuangan Harian Pribadi</span>
            </div>
        </header>

        <!-- Form Login -->
        <section class="login-card">
            <div class="login-kicker">
                <span class="login-kicker-dot"></span>
                KEUANGAN PRIBADI • AKSES AMAN
            </div>

            <div>
                <h1 class="login-title">Selamat Datang Kembali</h1>
                <p class="login-description">
                    Masuk ke akun DailyCash untuk memantau saldo, mencatat transaksi, dan mengelola keuangan pribadi Anda.
                </p>
            </div>

            <div class="login-divider"></div>

            <x-alert />

            <form action="{{ route('login.attempt') }}" method="POST" class="login-form" novalidate>
                @csrf

                <div>
                    <label for="email" class="login-label">
                        Alamat Email <span class="login-required">*</span>
                    </label>
                    <div class="login-input-wrap">
                        <svg class="login-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v9a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 16.5v-9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m4 7 8 6 8-6" />
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="login-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        >
                    </div>
                    @error('email')
                        <p class="login-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="login-label">
                        Password <span class="login-required">*</span>
                    </label>
                    <div class="login-input-wrap">
                        <svg class="login-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10V7a5 5 0 0110 0v3m-11 0h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14v3" />
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password Anda"
                            class="login-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        >
                    </div>
                    @error('password')
                        <p class="login-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-0.5">
                    <label for="remember" class="login-remember">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="login-submit">
                    <span>Masuk ke Dashboard</span>
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 12h14m-6-6 6 6-6 6" />
                    </svg>
                </button>
            </form>

            <div class="login-divider" style="margin: 20px 0 15px;"></div>

            <div class="login-register">
                Belum punya akun?
                <a href="{{ route('register') }}">Buat akun sekarang</a>
            </div>
        </section>

        <footer class="login-footer">
            &copy; {{ date('Y') }} <strong>DailyCash</strong> — Data keuangan terisolasi dan aman untuk setiap pengguna.
        </footer>
    </main>
</body>
</html>