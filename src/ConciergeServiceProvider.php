<?php

namespace Exodusanto\Concierge;

use Illuminate\Support\ServiceProvider;
use Exodusanto\Concierge\MakeMigrationCommand;

class ConciergeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-concierge.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-concierge');
    }
}
