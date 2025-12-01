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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DiscountTransactionController;
use App\Http\Controllers\ShelfMovementController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Guest Routes (Login, Register)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');

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

    // Superadmin Only - Owner access
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // Admin & Inventory Manager - Inventory Management
    Route::middleware('role:admin,inventory_manager')->group(function () {
        Route::resource('inventory', InventoryController::class)->except(['destroy']);

        // Stock In/Out Module
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/in', [StockController::class, 'stockIn'])->name('stock.in');
        Route::post('/stock/in', [StockController::class, 'processStockIn'])->name('stock.in.process');
        Route::get('/stock/out', [StockController::class, 'stockOut'])->name('stock.out');
        Route::post('/stock/out', [StockController::class, 'processStockOut'])->name('stock.out.process');

        // Shelf Restocking
        Route::get('/stock/restock', [StockController::class, 'restock'])->name('stock.restock');
        Route::post('/stock/restock', [StockController::class, 'processRestock'])->name('stock.restock.process');

        // Shelf Movements
        Route::get('/inventory/shelf-movements', [ShelfMovementController::class, 'index'])->name('inventory.shelf-movements.index');
        Route::get('/inventory/shelf-movements/create', [ShelfMovementController::class, 'create'])->name('inventory.shelf-movements.create');
        Route::post('/inventory/shelf-movements', [ShelfMovementController::class, 'store'])->name('inventory.shelf-movements.store');
        Route::get('/inventory/shelf-movements/{shelfMovement}', [ShelfMovementController::class, 'show'])->name('inventory.shelf-movements.show');

        // Stock Movements
        Route::get('/inventory/stock-movements', [StockMovementController::class, 'index'])->name('inventory.stock-movements.index');
        Route::get('/inventory/stock-movements/create', [StockMovementController::class, 'create'])->name('inventory.stock-movements.create');
        Route::post('/inventory/stock-movements', [StockMovementController::class, 'store'])->name('inventory.stock-movements.store');
        Route::get('/inventory/stock-movements/{stockMovement}', [StockMovementController::class, 'show'])->name('inventory.stock-movements.show');
    });

    // Admin Only - Additional Admin Permissions
    Route::middleware('role:admin')->group(function () {
        Route::delete('/inventory/{inventory}/void', [InventoryController::class, 'void'])->name('inventory.void');
        Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::resource('suppliers', SupplierController::class)->except(['show']);

        // Reports
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/senior-citizen', [ReportController::class, 'seniorCitizen'])->name('reports.senior-citizen');
        Route::get('/reports/pwd', [ReportController::class, 'pwd'])->name('reports.pwd');
    });

    // Admin & Inventory Manager - Inventory Reports
    Route::middleware('role:admin,inventory_manager')->group(function () {
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    });

    // Admin & Cashier - Sales & Customers
    Route::middleware('role:admin,cashier')->group(function () {
        Route::resource('sales', SalesController::class)->only(['index', 'show']);
        Route::resource('customers', CustomerController::class)->except(['show']);

        // Senior Citizen & PWD Discount Transactions
        Route::get('/discounts/senior-citizen', [DiscountTransactionController::class, 'seniorCitizenIndex'])->name('discounts.senior-citizen');
        Route::get('/discounts/pwd', [DiscountTransactionController::class, 'pwdIndex'])->name('discounts.pwd');
    });

    // Cashier Only - POS
    Route::middleware('role:cashier')->group(function () {
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/pos/verify-admin', [POSController::class, 'verifyAdmin'])->name('pos.verify-admin');
        Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    });
});
