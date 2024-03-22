<?php

namespace Bitoff\Feedback\Traits;

use Bitoff\Feedback\Application\Models\Level;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Level|null $level
 */
trait UserHaveLevels
{
    /**
     * @return HasMany
     */
    public function level(): HasMany
    {
        return $this->hasMany(Level::class, 'user_id');
    }
}
