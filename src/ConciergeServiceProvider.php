<?php

namespace Exodusanto\Concierge;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Exodusanto\Concierge\Contracts\RefreshApiTokenContract;

class ConciergeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('concierge.php'),
            ], 'concierge-config');
        }

        $this->loadBladeDirectives();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'concierge');
    }

    protected function loadBladeDirectives()
    {
        Blade::directive('concierge', function ($expression) {
            $args = explode(', ', $expression);

            $guard = preg_replace("/\"|\'/", '', $args[0] ?? '') ?? null;
            $tokenName = preg_replace("/\"|\'/", '', $args[1] ?? '') ?? null;

            if ($user = Auth::guard($guard)->user()) {
                if ($user instanceof RefreshApiTokenContract) {
                    $attributeName = $tokenName ?: $user->getApiTokenName();

                    $data = [
                        $attributeName => $user->getApiToken()
                    ];

                    $json = json_encode($data);

                    return "<script>__CONCIERGE__ = $json</script>";
                }
            }

            return '';
        });
    }
}
