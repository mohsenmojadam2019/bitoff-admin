<?php

namespace Bitoff\Syncer\Observers;

use App\Models\Credit as AppCredit;
use Bitoff\Mantis\Application\Models\Credit as MantisCredit;
use Bitoff\Syncer\Syncer;

class SyncerCreditObserver
{
    public function created(AppCredit|MantisCredit $credit): void
    {
        (new Syncer($credit))->run();
    }
}
