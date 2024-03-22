<?php

namespace Bitoff\Feedback\Providers;

use App\Models\Order;
use Bitoff\Feedback\Application\Policies\FeedbackPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class => FeedbackPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
