<?php

namespace App\Models\Traits\Relations;

use App\Models\UserAggregate;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property UserAggregate $aggregate
 */
trait AggregateUserData
{
    public function aggregate(): HasOne
    {
        return $this->hasOne(UserAggregate::class, 'user_id')
            ->withDefault();
    }
}
