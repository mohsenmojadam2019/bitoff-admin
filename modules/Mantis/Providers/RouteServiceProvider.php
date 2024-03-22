<?php

namespace Bitoff\Mantis\Providers;

use App\Support\Hash\HashId;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();

        Route::bind('offer', function ($offer) {
            if ($decode = HashId::decode($offer)) {
                return $decode[0];
            }
            abort(404);
        });

        Route::bind('trade', function ($trade) {
            if ($decode = HashId::decode($trade)) {
                return $decode[0];
            }
            abort(404);
        });

        $this->configureRateLimiting();

        $this->routes(function (): void {
            Route::middleware(['web', 'auth'])
                ->prefix('mantis')
                ->as('mantis.')
                ->group(__DIR__ . '/../Routes/web.php');
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by(optional($request->user())->id ?: $request->getRealIp()));
    }
}
