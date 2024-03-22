<?php

namespace Bitoff\Mantis\Providers;

use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Observers\CreditObserver;
use Illuminate\Support\ServiceProvider;

class MantisServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Model::preventLazyLoading(!$this->app->environment('production'));
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Mantis');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'Mantis');

        Credit::observe(CreditObserver::class);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
