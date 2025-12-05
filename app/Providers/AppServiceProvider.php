<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default pagination from settings
        $perPage = Cache::get('settings.pagination_per_page', 15);
        Paginator::useBootstrapFive(); // Or use default Tailwind pagination

        // Share pagination setting with all views
        view()->composer('*', function ($view) use ($perPage) {
            $view->with('perPage', $perPage);
        });
    }
}
