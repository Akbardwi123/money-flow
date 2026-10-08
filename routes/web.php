<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InvestmentAllocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('home');

Route::post('/demo-login', function () {
    $user = User::where('email', 'user@moneyflow.test')->first() ?? User::first();

    if ($user) {
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang di Demo MoneyFlow!');
    }

    return redirect()->route('login');
})->name('demo.login');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard & Real-Time Surplus Hub
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Pemasukan (Incomes)
    Route::resource('incomes', IncomeController::class)->except(['create', 'show']);

    // 2. Pencatatan Pengeluaran (Expenses: Priority & Flexible)
    Route::resource('expenses', ExpenseController::class)->except(['create', 'show']);

    // 4. Hub Alokasi Investasi (Surplus Investment Allocation)
    Route::resource('allocations', InvestmentAllocationController::class)->except(['create', 'show']);

    // 5. Dashboard Portofolio & Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
