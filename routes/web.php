<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WireframeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'root'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('accounts', AccountController::class);

    Route::get('transactions/{transaction}/proof', [TransactionController::class, 'proof'])
        ->name('transactions.proof');
    Route::resource('transactions', TransactionController::class);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    Route::post('budgets', [BudgetController::class, 'store'])->name('budgets.store');

    Route::get('wireframes', [WireframeController::class, 'index'])->name('wireframes.index');
});
