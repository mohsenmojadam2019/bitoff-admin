<?php

namespace Bitoff\Syncer\AcceptedUnits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SyncerAcceptedInterface
{
    public function setUser(Model|User $user): SyncerAcceptedInterface;

    /**
     * @param string $currency
     * @return Collection
     */
    public function getActives(string $currency): Collection;

    /**
     * @param string $currency
     * @return Collection
     */
    public function getInActives(string $currency): Collection;

    public function verifierForEarnedAmount(int $id): void;

    public function verifierForLoseAmount(int $id): void;
}
