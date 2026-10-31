<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WireframeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::resource('categories', CategoryController::class);
Route::resource('accounts', AccountController::class);

Route::get('transactions/{transaction}/proof', [TransactionController::class, 'proof'])
    ->name('transactions.proof');
Route::resource('transactions', TransactionController::class);

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

Route::post('budgets', [BudgetController::class, 'store'])->name('budgets.store');

Route::get('wireframes', [WireframeController::class, 'index'])->name('wireframes.index');
