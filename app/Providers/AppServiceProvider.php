<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Este método se utiliza para registrar servicios personalizados en el contenedor de servicios.
        // Actualmente no se requiere registrar ningún servicio específico.
        // Si en el futuro necesitas enlazar clases o servicios personalizados, agrégalos aquí.
        // throw new \LogicException('Implementar el registro de servicios si es necesario.');
    }

    public function boot(Request $request): void
    {
        if ($this->app->environment('production')) {
            $request->setTrustedProxies(
                [$request->getClientIp()],
                SymfonyRequest::HEADER_X_FORWARDED_FOR |
                SymfonyRequest::HEADER_X_FORWARDED_HOST |
                SymfonyRequest::HEADER_X_FORWARDED_PORT |
                SymfonyRequest::HEADER_X_FORWARDED_PROTO
            );

            URL::forceScheme('https');
        }
    }
}
