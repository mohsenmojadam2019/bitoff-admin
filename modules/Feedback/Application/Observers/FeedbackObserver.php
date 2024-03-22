<?php

namespace Bitoff\Feedback\Application\Observers;

use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Application\Services\LevelService;
use Throwable;

class FeedbackObserver
{
    public function created(Feedback $feedback): void
    {
        try {
            LevelService::handleNewFeedback($feedback);
        } catch (Throwable $exception) {
            logger()->error($exception->getMessage(), $exception->getTrace());
        }
    }
}
