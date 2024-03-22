<?php

namespace Bitoff\Feedback\Traits;

use Bitoff\Feedback\Application\Models\Feedback;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Feedback[]|Collection|\Illuminate\Support\Collection $feedback
 */
trait HaveFeedback
{
    public function feedback()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }

    public function isFeedbackDone(): bool
    {
        return $this->feedback()->count() >= 2;
    }

}
