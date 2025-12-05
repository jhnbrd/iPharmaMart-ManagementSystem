<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Cache;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic database backups
Schedule::command('backup:database')->daily()->at('02:00')->when(function () {
    return Cache::get('settings.auto_backup_enabled', false);
});
