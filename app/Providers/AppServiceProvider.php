<?php

namespace App\Providers;

use App\Services\Contract\ServiceManager;
use App\Services\Manager\ServiceManager as Manager;
use FFMpeg\FFMpeg;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(FFMpeg::class, function ($app) {
            return FFMpeg::create();
        });

        $this->app->singleton(ServiceManager::class, function ($app) {
            return new Manager();
        });

    }
}
