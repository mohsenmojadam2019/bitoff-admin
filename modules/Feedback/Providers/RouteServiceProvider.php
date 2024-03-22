<?php

namespace Bitoff\Feedback\Providers;

use Bitoff\Feedback\Utilities\HashId;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Below items are route parameters, They will decode before controller execution.
     */
    protected array $decodes = [
        'order_id',
    ];

    /**
     * define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function (): void {
            Route::middleware(['web', 'auth'])
                ->prefix('feedback')
                ->name('feedback.')
                ->group(__DIR__.'/../Routes/web.php');
        });

        $this->decodeRouteParameters();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by(optional($request->user())->id ?: $request->getRealIp()));
    }

    private function decodeRouteParameters(): void
    {
        foreach ($this->decodes as $decode) {
            Route::bind($decode, function ($value) {
                try {
                    return HashId::decode($value)[0];
                } catch (\Throwable) {
                    abort(404, 'Not Found');
                }
            });
        }
    }
}
