<?php

namespace Bitoff\Syncer\Providers;

use App\Models\Credit as AppCredit;
use Bitoff\Mantis\Application\Models\Credit as MantisCredit;
use Bitoff\Syncer\Observers\SyncerCreditObserver;
use Illuminate\Support\ServiceProvider;

class SyncerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AppCredit::observe(SyncerCreditObserver::class);
        MantisCredit::observe(SyncerCreditObserver::class);
    }

    public function register(): void
    {
    }
}
