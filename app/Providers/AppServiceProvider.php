<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            // Laravel confía en los proxies (como Azure)
            Request::setTrustedProxies(
                [Request::getClientIp()],
                Request::HEADER_X_FORWARDED_ALL
            );

            // Forzar HTTPS
            URL::forceScheme('https');
        }
    }
}
