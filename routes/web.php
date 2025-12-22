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
use App\Http\Controllers\UnifiedReportController;
use App\Http\Controllers\DiscountTransactionController;
use App\Http\Controllers\UnifiedDiscountController;
use App\Http\Controllers\ShelfMovementController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
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

    // Profile - All authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Users management: Admin and Superadmin
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Audit logs: Superadmin only
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // Admin & Inventory Manager - Inventory Management
    Route::middleware('role:admin,inventory_manager')->group(function () {
        // Shelf Movements - MUST be before inventory resource route
        Route::get('/inventory/shelf-movements', [ShelfMovementController::class, 'index'])->name('inventory.shelf-movements.index');
        Route::get('/inventory/shelf-movements/create', [ShelfMovementController::class, 'create'])->name('inventory.shelf-movements.create');
        Route::post('/inventory/shelf-movements', [ShelfMovementController::class, 'store'])->name('inventory.shelf-movements.store');
        Route::get('/inventory/shelf-movements/{shelfMovement}', [ShelfMovementController::class, 'show'])->name('inventory.shelf-movements.show');

        // Inventory Resource - This catches /inventory/{id} so must be after specific routes
        Route::resource('inventory', InventoryController::class)->except(['destroy']);

        // Stock Movements Module (Unified Stock In/Out)
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/{stockMovement}', [StockController::class, 'show'])->name('stock.show');
        Route::get('/stock/in/create', [StockController::class, 'stockIn'])->name('stock.in');
        Route::post('/stock/in', [StockController::class, 'processStockIn'])->name('stock.in.process');
        Route::get('/stock/out/create', [StockController::class, 'stockOut'])->name('stock.out');
        Route::post('/stock/out', [StockController::class, 'processStockOut'])->name('stock.out.process');

        // Shelf Restocking
        Route::get('/stock/restock', [StockController::class, 'restock'])->name('stock.restock');
        Route::post('/stock/restock', [StockController::class, 'processRestock'])->name('stock.restock.process');
    });

    // Admin Only - Additional Admin Permissions
    Route::middleware('role:admin')->group(function () {
        Route::delete('/inventory/{inventory}/void', [InventoryController::class, 'void'])->name('inventory.void');
        Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::resource('suppliers', SupplierController::class)->except(['show']);

        // Unified Reports
        Route::get('/reports', [UnifiedReportController::class, 'index'])->name('reports.index');

        // Legacy report routes (for backward compatibility)
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/senior-citizen', [ReportController::class, 'seniorCitizen'])->name('reports.senior-citizen');
        Route::get('/reports/pwd', [ReportController::class, 'pwd'])->name('reports.pwd');

        // PDF Report Generation
        Route::get('/reports/sales/pdf', [ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
        Route::get('/reports/senior-citizen/pdf', [ReportController::class, 'seniorCitizenPdf'])->name('reports.senior-citizen.pdf');
        Route::get('/reports/pwd/pdf', [ReportController::class, 'pwdPdf'])->name('reports.pwd.pdf');

        // Void sale (Admin only)
        Route::post('/sales/{sale}/void', [SalesController::class, 'void'])->name('sales.void');
    });

    // Admin & SuperAdmin - General Settings
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/delete-old-data', [SettingsController::class, 'deleteOldData'])->name('settings.delete-old-data');
        Route::post('/settings/backup-database', [SettingsController::class, 'backupDatabase'])->name('settings.backup-database');
        Route::get('/settings/list-backups', [SettingsController::class, 'listBackups'])->name('settings.list-backups');
        Route::get('/settings/download-backup/{filename}', [SettingsController::class, 'downloadBackup'])->name('settings.download-backup');
        Route::delete('/settings/delete-backup/{filename}', [SettingsController::class, 'deleteBackup'])->name('settings.delete-backup');
    });

    // Admin & Inventory Manager - Inventory Reports
    Route::middleware('role:admin,inventory_manager')->group(function () {
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])->name('reports.inventory.pdf');
    });

    // Admin & Cashier - Sales & Customers
    Route::middleware('role:admin,cashier')->group(function () {
        Route::resource('sales', SalesController::class)->only(['index', 'show']);
        Route::resource('customers', CustomerController::class)->except(['show']);

        // Unified Discount Transactions
        Route::get('/discounts', [UnifiedDiscountController::class, 'index'])->name('discounts.index');

        // Legacy discount routes (for backward compatibility)
        Route::get('/discounts/senior-citizen', [DiscountTransactionController::class, 'seniorCitizenIndex'])->name('discounts.senior-citizen');
        Route::get('/discounts/pwd', [DiscountTransactionController::class, 'pwdIndex'])->name('discounts.pwd');
    });

    // Cashier Only - POS
    Route::middleware('role:cashier')->group(function () {
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        // AJAX helper to fetch categories relevant for a product type
        Route::get('/pos/categories', [POSController::class, 'categories'])->name('pos.categories');
        Route::post('/pos/verify-admin', [POSController::class, 'verifyAdmin'])->name('pos.verify-admin');
        Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    });
});
