<?php

namespace Bitoff\Syncer\AcceptedUnits;

use App\Models\User;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Services\Calculate\CalculateServiceFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OfferSyncerAccepted implements SyncerAcceptedInterface
{
    private CalculateServiceFactory $calculateServiceFactory;
    private Collection $offers;
    private Model|User $user;

    public function __construct()
    {
        $this->calculateServiceFactory = resolve(CalculateServiceFactory::class);
        $this->offers = collect();
    }

    public function getActives(string $currency): Collection
    {
        $this->offers = Offer::with('trades')
            ->whereIsBuy(Offer::SELL)
            ->whereCurrency($currency)
            ->whereOffererId($this->user->id)
            ->whereActive(Offer::ACTIVE)
            ->withoutTrashed()
            ->get(['id', 'currency', 'created_at', 'max', 'rate', 'fee']);

        return $this->mapping();
    }

    public function getInActives(string $currency): Collection
    {
        return collect();
    }

    private function mapping(): Collection
    {
        return $this->offers->map(fn(Offer $offer) => [
            'id' => $offer->id,
            'type' => Offer::class,
            'currency' => $offer->currency,
            'created_at' => $offer->created_at->timestamp,
            'amount' => $this->calculateOfferAmount($offer),
        ]);
    }

    public function verifierForEarnedAmount(int $id): void {}

    public function verifierForLoseAmount(int $id): void {
        $offer = $this->findOffer($id);
        $offer->update(['active' => Offer::INACTIVE]);
    }

    /**
     * @param Offer $offer
     * @return float
     */
    private function calculateOfferAmount(Offer $offer): float
    {
        $offerCalculateFactory = $this->calculateServiceFactory
            ->currency($offer->currency)
            ->createCalculator()
            ->calculate(
                $offer->max,
                ['rate' => $offer->rate, 'fee' => $offer->fee],
            );

        return (float) $offerCalculateFactory['subtractedAmount'];
    }

    /**
     * @param $offerId
     * @return Offer
     */
    private function findOffer($offerId): Offer
    {
        $this->offers = $this->offers->keyBy('id');
        return $this->offers->get($offerId);
    }

    public function setUser(Model|User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
