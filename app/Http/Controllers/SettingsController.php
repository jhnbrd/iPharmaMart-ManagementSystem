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
        $settings = [
            'pagination_per_page' => Cache::get('settings.pagination_per_page', 15),
            'data_deletion_age_days' => Cache::get('settings.data_deletion_age_days', 1825),
            'expiry_alert_days' => Cache::get('settings.expiry_alert_days', 7),
            'low_stock_alert_enabled' => Cache::get('settings.low_stock_alert_enabled', true),
            'auto_backup_enabled' => Cache::get('settings.auto_backup_enabled', false),
        ];

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
            'data_deletion_age_days' => 'required|integer|min:1095|max:3650',
            'expiry_alert_days' => 'required|integer|min:1|max:90',
            'low_stock_alert_enabled' => 'boolean',
            'auto_backup_enabled' => 'boolean',
        ]);

        // Store settings in cache (permanent)
        Cache::forever('settings.pagination_per_page', $validated['pagination_per_page']);
        Cache::forever('settings.data_deletion_age_days', $validated['data_deletion_age_days']);
        Cache::forever('settings.expiry_alert_days', $validated['expiry_alert_days']);
        Cache::forever('settings.low_stock_alert_enabled', $request->has('low_stock_alert_enabled'));
        Cache::forever('settings.auto_backup_enabled', $request->has('auto_backup_enabled'));

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function clearCache()
    {
        Cache::flush();
        return redirect()->route('settings.index')
            ->with('success', 'Cache cleared successfully!');
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
