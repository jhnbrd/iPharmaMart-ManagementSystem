<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Guest Routes (Login, Register)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard - All roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Point of Sale - Superadmin, Admin, Cashier only
    Route::middleware('role:superadmin,admin,cashier')->group(function () {
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::resource('sales', SalesController::class)->except(['edit', 'update', 'destroy']);
        Route::resource('customers', CustomerController::class)->except(['show']);
    });

    // Inventory Management - Superadmin, Admin, Inventory Manager only
    Route::middleware('role:superadmin,admin,inventory_manager')->group(function () {
        Route::resource('inventory', InventoryController::class);
        Route::resource('suppliers', SupplierController::class)->except(['show']);

        // Stock In/Out Module
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/in', [StockController::class, 'stockIn'])->name('stock.in');
        Route::post('/stock/in', [StockController::class, 'processStockIn'])->name('stock.in.process');
        Route::get('/stock/out', [StockController::class, 'stockOut'])->name('stock.out');
        Route::post('/stock/out', [StockController::class, 'processStockOut'])->name('stock.out.process');
    });

    // User Management - Superadmin and Admin only
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Audit Logs - Superadmin only
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
