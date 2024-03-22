<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repository\EloquentRepositoryInterface::class,\App\Repository\Eloquent\BaseRepository::class);
        $this->app->bind(\App\Repository\OrderRepositoryInterface::class,\App\Repository\Eloquent\OrderRepository::class);
        $this->app->bind(\App\Repository\UserRepositoryInterface::class,\App\Repository\Eloquent\UserRepository::class);
        $this->app->bind(\App\Repository\PermissionRepositoryInterface::class,\App\Repository\Eloquent\PermissionRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
