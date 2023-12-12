<?php

namespace App\Providers;

use App\Services\ClienteService;
use App\Services\ImportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClienteService::class, function ($app) {
            return new ClienteService();
        });

        $this->app->bind(ImportService::class, function ($app) {
            return new ImportService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
