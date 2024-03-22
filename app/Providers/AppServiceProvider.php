<?php

namespace App\Providers;

use App\Api\BitCoinRate;
use App\Models\Credit;
use App\Observers\CreditObserver;
use App\Services\Settings\CachedSettingsRepository;
use App\Services\Settings\SettingsContract;
use App\Support\Http\ArrayResponse;
use App\Support\Http\HttpResponseInterface;
use App\Support\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SettingsContract::class, CachedSettingsRepository::class);
        $this->app->bind(HttpResponseInterface::class, JsonResponse::class);
        $this->app->when(BitCoinRate::class)
            ->needs(HttpResponseInterface::class)
            ->give(ArrayResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Str::macro('humanize', function ($string, $separator = '_') {
            return ucfirst(str_replace($separator, ' ', $string));
        });

        if ($this->app->environment('local')) {
            \Debugbar::enable();
        }
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        Credit::observe(CreditObserver::class);
    }
}
