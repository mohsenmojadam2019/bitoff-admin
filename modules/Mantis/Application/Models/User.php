<?php

namespace Bitoff\Mantis\Application\Models;

use App\Models\Traits\Relations\AggregateUserData;
use Bitoff\Mantis\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin AggregateUserData
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use AggregateUserData;

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'offerer_id');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class, 'trader_id');
    }
}
