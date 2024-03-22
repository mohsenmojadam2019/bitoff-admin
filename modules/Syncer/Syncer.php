<?php

namespace Bitoff\Syncer;

use App\Models\Credit as AppCredit;
use App\Models\Order;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Models\Credit as MantisCredit;
use Bitoff\Syncer\AcceptedUnits\OfferSyncerAccepted;
use Bitoff\Syncer\AcceptedUnits\OrderSyncerAccepted;
use Bitoff\Syncer\AcceptedUnits\SyncerAcceptedInterface;
use Illuminate\Support\Collection;

class Syncer
{
    private AppCredit|MantisCredit $credit;

    private array|Collection $accepts = [
        Order::class => OrderSyncerAccepted::class,
        Offer::class => OfferSyncerAccepted::class,
    ];

    public function __construct(AppCredit|MantisCredit $credit)
    {
        $this->credit = $credit;
        $this->accepts = collect($this->accepts)->map(fn($accepted) => resolve($accepted)->setUser($credit->user));
    }

    public function run(): void
    {
        if ($this->credit->amount === 0) {
            return;
        }

        $this->credit->amount > 0 ? $this->increaseCredit() : $this->decreaseCredit();
    }

    private function increaseCredit(): void
    {
        $inActiveCollection = collect();
        $this->accepts->each(function (SyncerAcceptedInterface $accepted) use (&$inActiveCollection) {
            $inActiveCollection = $inActiveCollection->merge($accepted->getInActives($this->credit->currency ?? AppCredit::CURRENCY_BTC));
        });

        if ($inActiveCollection->isEmpty()) {
            return;
        }

        $inActiveCollection = $inActiveCollection->sortByDesc('created_at', SORT_NUMERIC);
        $userCredit = $this->getUserCredit();

        while ($userCredit > 0 && $inActiveCollection->isNotEmpty()) {
            $item = $inActiveCollection->shift();
            if ($userCredit < $item['amount']) {
                continue;
            }

            $this->accepts->get($item['type'])
                ->verifierForEarnedAmount($item['id']);

            $userCredit -= $item['amount'];
        }
    }

    private function decreaseCredit(): void
    {
        $activeCollection = collect();
        $this->accepts->each(function (SyncerAcceptedInterface $accepted) use (&$activeCollection) {
            $activeCollection = $activeCollection->merge($accepted->getActives($this->credit->currency ?? AppCredit::CURRENCY_BTC));
        });

        if ($activeCollection->isEmpty()) {
            return;
        }

        $activeCollection = $activeCollection->sortByDesc('created_at');
        $userCredit = $this->getUserCredit();

        while ($activeCollection->isNotEmpty()) {
            $item = $activeCollection->shift();

            if ($userCredit >= $item['amount']) {
                $userCredit -= $item['amount'];
                continue;
            }

            $this->accepts->get($item['type'])
                ->verifierForLoseAmount($item['id']);
        }
    }

    private function getUserCredit(): float
    {
        $user = $this->credit->user;
        return (float) ($this->credit->currency === AppCredit::CURRENCY_USDT ?
            $user->aggregate->usdtCreditSum->getForUpdate() : $user->aggregate->btcCreditSum->getForUpdate());
    }
}
