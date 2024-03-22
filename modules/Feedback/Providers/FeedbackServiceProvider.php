<?php

namespace Bitoff\Feedback\Providers;

use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Application\Observers\FeedbackObserver;
use Bitoff\Feedback\Application\Repositories\FeedbackRepository;
use Bitoff\Feedback\Application\Repositories\FeedbackRepositoryInterface;
use Bitoff\Feedback\Application\Repositories\LevelRepository;
use Bitoff\Feedback\Application\Repositories\LevelRepositoryInterface;
use Bitoff\Feedback\Application\Repositories\StoreFeedbackRepository;
use Bitoff\Feedback\Application\Repositories\StoreFeedbackRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        Feedback::observe(FeedbackObserver::class);
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        $this->app->bind(StoreFeedbackRepositoryInterface::class, StoreFeedbackRepository::class);
        $this->app->bind(FeedbackRepositoryInterface::class, FeedbackRepository::class);
        $this->app->bind(LevelRepositoryInterface::class, LevelRepository::class);
    }
}
