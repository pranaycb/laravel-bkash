<?php

namespace PranayCb\LaravelBkash;

use Illuminate\Support\ServiceProvider;

class BkashServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__ . '/../config/bkash.php' => config_path('bkash.php'),
        ], 'config');
    }

    public function register()
    {
        // Register the Bkash service
        $this->app->singleton(BkashService::class, function ($app) {
            return new BkashService();
        });
    }
}
