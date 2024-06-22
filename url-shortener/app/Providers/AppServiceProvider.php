<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UrlValidationService;
use App\Services\UrlSafetyService;
use App\Services\UrlShorteningService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlValidationService::class);
        $this->app->singleton(UrlSafetyService::class);
        $this->app->singleton(UrlShorteningService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
