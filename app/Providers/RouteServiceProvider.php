<?php

namespace App\Providers;

use App\Support\Hash\HashId;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::bind('order', function ($order) {
            if ($decode = HashId::decode($order)) {
                return $decode[0];
            }
            abort(404);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web', 'auth', 'acl'])
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));

        Route::middleware(['web'])
            ->namespace($this->namespace . '\Auth')
            ->group(base_path('routes/auth.php'));

        Route::middleware(['web','auth','acl'])
            ->namespace($this->namespace)
            ->group(base_path('routes/version_two.php'));
    }

}
