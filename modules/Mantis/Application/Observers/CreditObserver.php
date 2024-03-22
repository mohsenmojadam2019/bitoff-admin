<?php

namespace Bitoff\Mantis\Application\Observers;

use App\Models\UserAggregate;
use Bitoff\Mantis\Application\Models\Credit;

class CreditObserver
{
    /**
     * Handle the Credit "created" event.
     */
    public function created(Credit $credit)
    {
        $this->updateUserAggregate($credit);
    }

    /**
     * Handle the Credit "updated" event.
     */
    public function updated(Credit $credit)
    {
        $oldStatus = $credit->getDirty()['status'] ?? Credit::STATUS_CONFIRMATION;
        $newStatus = $credit->status ?? Credit::STATUS_CONFIRMATION;

        if ($oldStatus === Credit::STATUS_UNCONFIRMATION && $newStatus === Credit::STATUS_CONFIRMATION) {
            $this->updateUserAggregate($credit);
        }
    }

    /**
     * Handle the Credit "deleted" event.
     */
    public function deleted(Credit $credit)
    {
        $this->updateUserAggregate($credit, true);
    }

    /**
     * Handle the Credit "force deleted" event.
     */
    public function forceDeleted(Credit $credit)
    {
        $this->updateUserAggregate($credit, true);
    }

    private function updateUserAggregate(Credit $credit, bool $isDeleted = false)
    {
        if (!isset($credit->status)) {
            $credit->status = Credit::STATUS_CONFIRMATION;
        }

        if ($credit->status !== Credit::STATUS_CONFIRMATION) {
            return;
        }

        $aggregate = $credit->user->aggregate;
        $method = $credit->currency === Credit::CURRENCY_USDT ? UserAggregate::USDT_CREDITS_SUM : UserAggregate::BTC_CREDITS_SUM;
        $field = $aggregate->{$method}->getField();

        if (!$aggregate->exists || !isset($aggregate->{$field})) {
            $aggregate->{$method}->refresh();
        } else {
            $oldAmount = $aggregate->{$field};
            $newAmount = $isDeleted ? $oldAmount - $credit->amount : $oldAmount + $credit->amount;
            $aggregate->{$method}->set($newAmount);
        }
    }
}
