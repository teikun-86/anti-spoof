<?php

namespace Teikun86\AntiSpoof\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Teikun86\AntiSpoof\Actions\DetectSpoofing;
use Teikun86\AntiSpoof\AntiSpoof;

class AntiSpoofServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/anti-spoof.php' => config_path('anti-spoof.php'),
        ], 'anti-spoof-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/anti-spoof.php',
            'anti-spoof'
        );

        $this->app->singleton('anti-spoof', function () {
            return $this->app->make(AntiSpoof::class);
        });
        
        $this->app->booted(function () {
            $this->app->make(Router::class)
                ->aliasMiddleware('anti-spoof', DetectSpoofing::class);
        });
    }
}