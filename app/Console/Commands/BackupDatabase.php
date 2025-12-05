<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--manual : Manual backup trigger}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the SQLite database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $databasePath = database_path('database.sqlite');

            // Check if database exists
            if (!File::exists($databasePath)) {
                $this->error('Database file not found at: ' . $databasePath);
                return 1;
            }

            // Create backups directory if it doesn't exist using Storage
            if (!\Storage::disk('local')->exists('backups')) {
                \Storage::disk('local')->makeDirectory('backups');
            }

            // Generate backup filename with timestamp
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $backupType = $this->option('manual') ? 'manual' : 'auto';
            $backupFilename = "database_backup_{$backupType}_{$timestamp}.sqlite";

            // Read database file and store using Storage
            $databaseContent = File::get($databasePath);
            \Storage::disk('local')->put('backups/' . $backupFilename, $databaseContent);

            // Delete old backups (keep last 30 days)
            $this->cleanOldBackups();

            $backupPath = storage_path('app/backups/' . $backupFilename);
            $this->info('Database backup created successfully: ' . $backupFilename);
            $this->info('Backup location: ' . $backupPath);

            return 0;
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clean old backup files (keep last 30 days)
     */
    private function cleanOldBackups($backupDir = null)
    {
        $files = \Storage::disk('local')->files('backups');
        $cutoffDate = Carbon::now()->subDays(30);

        foreach ($files as $file) {
            $lastModified = \Storage::disk('local')->lastModified($file);
            if (Carbon::createFromTimestamp($lastModified)->lt($cutoffDate)) {
                \Storage::disk('local')->delete($file);
                $this->info('Deleted old backup: ' . basename($file));
            }
        }
    }
}
