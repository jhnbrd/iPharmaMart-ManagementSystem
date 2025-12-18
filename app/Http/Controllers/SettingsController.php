<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\ProductBatch;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function index()
    {
        // Load settings from file first, then from cache, then defaults
        $defaults = [
            'pagination_per_page' => 15,
            'data_deletion_age_days' => 1825,
            'expiry_alert_days' => 7,
            'low_stock_alert_enabled' => true,
            'auto_backup_enabled' => false,
        ];

        // Try to load from JSON file
        $fileSettings = [];
        if (Storage::exists('settings.json')) {
            $fileSettings = json_decode(Storage::get('settings.json'), true) ?? [];
        }

        // Merge settings: file > cache > defaults
        $settings = [
            'pagination_per_page' => $fileSettings['pagination_per_page'] ?? Cache::get('settings.pagination_per_page', $defaults['pagination_per_page']),
            'data_deletion_age_days' => $fileSettings['data_deletion_age_days'] ?? Cache::get('settings.data_deletion_age_days', $defaults['data_deletion_age_days']),
            'expiry_alert_days' => $fileSettings['expiry_alert_days'] ?? Cache::get('settings.expiry_alert_days', $defaults['expiry_alert_days']),
            'low_stock_alert_enabled' => $fileSettings['low_stock_alert_enabled'] ?? Cache::get('settings.low_stock_alert_enabled', $defaults['low_stock_alert_enabled']),
            'auto_backup_enabled' => $fileSettings['auto_backup_enabled'] ?? Cache::get('settings.auto_backup_enabled', $defaults['auto_backup_enabled']),
        ];

        // Sync cache with file settings if file exists
        if (!empty($fileSettings)) {
            Cache::forever('settings.pagination_per_page', $settings['pagination_per_page']);
            Cache::forever('settings.data_deletion_age_days', $settings['data_deletion_age_days']);
            Cache::forever('settings.expiry_alert_days', $settings['expiry_alert_days']);
            Cache::forever('settings.low_stock_alert_enabled', $settings['low_stock_alert_enabled']);
            Cache::forever('settings.auto_backup_enabled', $settings['auto_backup_enabled']);
        }

        // Get expiring products (within expiry_alert_days)
        $expiryAlertDays = $settings['expiry_alert_days'];
        $expiringBatches = ProductBatch::with('product')
            ->where('expiry_date', '<=', Carbon::now()->addDays($expiryAlertDays))
            ->where('expiry_date', '>', Carbon::now())
            ->whereRaw('(shelf_quantity + back_quantity) > 0')
            ->orderBy('expiry_date', 'asc')
            ->get();

        return view('settings.index', compact('settings', 'expiringBatches'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'pagination_per_page' => 'required|integer|min:5|max:100',
            'data_retention_years' => 'required|integer|min:3|max:10',
            'expiry_alert_days' => 'required|integer|min:1|max:90',
            'low_stock_alert_enabled' => 'nullable|boolean',
            'auto_backup_enabled' => 'nullable|boolean',
        ]);

        // Handle checkbox values (checkboxes don't send value when unchecked)
        $lowStockEnabled = $request->has('low_stock_alert_enabled') ? true : false;
        $autoBackupEnabled = $request->has('auto_backup_enabled') ? true : false;

        // Convert years to days for internal storage
        $retentionDays = (int)$validated['data_retention_years'] * 365;

        // Store settings in a JSON file for persistence
        $settings = [
            'pagination_per_page' => (int)$validated['pagination_per_page'],
            'data_deletion_age_days' => $retentionDays,
            'expiry_alert_days' => (int)$validated['expiry_alert_days'],
            'low_stock_alert_enabled' => $lowStockEnabled,
            'auto_backup_enabled' => $autoBackupEnabled,
        ];

        Storage::put('settings.json', json_encode($settings, JSON_PRETTY_PRINT));

        // Also store in cache for quick access
        Cache::forever('settings.pagination_per_page', $settings['pagination_per_page']);
        Cache::forever('settings.data_deletion_age_days', $settings['data_deletion_age_days']);
        Cache::forever('settings.expiry_alert_days', $settings['expiry_alert_days']);
        Cache::forever('settings.low_stock_alert_enabled', $settings['low_stock_alert_enabled']);
        Cache::forever('settings.auto_backup_enabled', $settings['auto_backup_enabled']);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function clearCache()
    {
        // Save current settings before clearing cache
        $currentSettings = [
            'pagination_per_page' => Cache::get('settings.pagination_per_page', 15),
            'data_deletion_age_days' => Cache::get('settings.data_deletion_age_days', 1825),
            'expiry_alert_days' => Cache::get('settings.expiry_alert_days', 7),
            'low_stock_alert_enabled' => Cache::get('settings.low_stock_alert_enabled', true),
            'auto_backup_enabled' => Cache::get('settings.auto_backup_enabled', false),
        ];

        // Clear all cache
        Cache::flush();

        // Restore settings to cache
        Cache::forever('settings.pagination_per_page', $currentSettings['pagination_per_page']);
        Cache::forever('settings.data_deletion_age_days', $currentSettings['data_deletion_age_days']);
        Cache::forever('settings.expiry_alert_days', $currentSettings['expiry_alert_days']);
        Cache::forever('settings.low_stock_alert_enabled', $currentSettings['low_stock_alert_enabled']);
        Cache::forever('settings.auto_backup_enabled', $currentSettings['auto_backup_enabled']);

        return redirect()->route('settings.index')
            ->with('success', 'Cache cleared successfully! (Settings preserved)');
    }

    public function deleteOldData()
    {
        $days = Cache::get('settings.data_deletion_age_days', 1825);
        $cutoffDate = Carbon::now()->subDays($days);

        // This is a placeholder - actual deletion should be done carefully
        // You might want to soft-delete or archive instead of hard-delete
        $deletedCount = 0;

        // Example: Delete old sales records (be cautious with this in production)
        // $deletedCount = Sale::where('created_at', '<', $cutoffDate)->count();
        // Sale::where('created_at', '<', $cutoffDate)->delete();

        return redirect()->route('settings.index')
            ->with('info', "Data retention policy: Records older than {$days} days can be archived. Manual review recommended.");
    }

    public function backupDatabase()
    {
        try {
            // Run the backup command
            Artisan::call('backup:database', ['--manual' => true]);

            $output = Artisan::output();

            return redirect()->route('settings.index')
                ->with('success', 'Database backup created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function listBackups()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        try {
            if (Storage::disk('local')->exists('backups')) {
                $files = Storage::disk('local')->files('backups');

                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'sqlite') {
                        $backups[] = [
                            'filename' => basename($file),
                            'size' => Storage::disk('local')->size($file),
                            'date' => Storage::disk('local')->lastModified($file),
                        ];
                    }
                }

                // Sort by date descending
                usort($backups, function ($a, $b) {
                    return $b['date'] - $a['date'];
                });
            }
        } catch (\Exception $e) {
            Log::error('Error listing backups: ' . $e->getMessage());
        }

        return response()->json($backups);
    }

    public function downloadBackup($filename)
    {
        $filePath = 'backups/' . $filename;

        if (!Storage::exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        return Storage::download($filePath);
    }

    public function deleteBackup($filename)
    {
        try {
            $filePath = 'backups/' . $filename;

            if (!Storage::exists($filePath)) {
                return response()->json(['error' => 'Backup file not found'], 404);
            }

            Storage::delete($filePath);

            return response()->json(['success' => true, 'message' => 'Backup deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete backup: ' . $e->getMessage()], 500);
        }
    }
}
