<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            Request::setTrustedProxies(
                [Request::getClientIp()],
                Request::HEADER_X_FORWARDED_ALL
            );

            URL::forceScheme('https');
        }
    }
}
