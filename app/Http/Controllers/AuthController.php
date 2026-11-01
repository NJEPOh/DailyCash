<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthController extends Controller
{
    /**
     * Redirect root URL based on authentication state.
     */
    public function root(): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }

    /**
     * Display the login view.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, '.Auth::user()?->name.'!');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
            ])
            ->onlyInput('email');
    }

    /**
     * Display the registration view.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request with default categories in an atomic transaction.
     */
    public function register(RegisterRequest $request, DefaultCategoryService $defaultCategoryService): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $user = DB::transaction(function () use ($validated, $defaultCategoryService): User {
                $newUser = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ]);

                $defaultCategoryService->createForUser($newUser);

                return $newUser;
            });
        } catch (Throwable) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Terjadi kesalahan saat menyiapkan akun dan kategori awal Anda. Silakan coba lagi.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Akun berhasil dibuat! Kategori pemasukan dan pengeluaran awal telah disiapkan untuk Anda.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah berhasil keluar dari aplikasi.');
    }
}
