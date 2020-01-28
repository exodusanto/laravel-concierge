<?php

namespace Exodusanto\Concierge\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Exodusanto\Concierge\Contracts\RefreshApiTokenContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RefreshApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->isMethod('GET') && $user = Auth::guard($guard)->user()) {
            $provider = $this->getProviderName(get_class($user));

            if ($provider && $user instanceof RefreshApiTokenContract) {
                $this->refreshTokenIfExpired($user, $provider);
            }
        }

        return $next($request);
    }

    /**
     * Get provider name of current model
     *
     * @param string $model
     * @return string|null
     */
    protected function getProviderName($model)
    {
        $providers = new Collection(config("auth.providers"));

        return $providers->search(function ($provider) use ($model) {
            return $provider['model'] === $model;
        });
    }

    /**
     * Check if user token is expired and run refresh method
     *
     * @param RefreshApiTokenContract $user
     * @param string $provider
     * @return void
     */
    protected function refreshTokenIfExpired(RefreshApiTokenContract $user, $provider)
    {
        if ($timeout = $this->getTimeout($provider)) {
            /** @var Carbon $lastRefreshedAt */
            $lastRefreshedAt = $user->{$user->getApiTokenRefreshedAtName()};

            if (!$lastRefreshedAt || now()->gt($lastRefreshedAt->addSeconds($timeout))) {
                $user->refreshApiToken();
            }
        }
    }

    /**
     * Get timeout value of provider
     *
     * @param string $provider
     * @return int|bool
     */
    protected function getTimeout($provider)
    {
        return config("concierge.tokens_lifetime.{$provider}", false);
    }
}
