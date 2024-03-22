<?php

namespace Bitoff\Feedback\Traits;

use App\Models\Order;
use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Application\Services\LevelService;
use Bitoff\Mantis\Application\Models\Trade;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $feedbackCount
 * @property-read Feedback[]|Collection|\Illuminate\Support\Collection $feedbackTo
 * @property-read Feedback[]|Collection|\Illuminate\Support\Collection $feedbackFrom
 */
trait UserHaveFeedbacks
{
    public function feedbackTo(): HasMany
    {
        return $this->hasMany(Feedback::class, 'to_user_id');
    }

    public function feedbackFrom(): HasMany
    {
        return $this->hasMany(Feedback::class, 'from_user_id');
    }

    protected function feedbackCount(): Attribute
    {
        $positives = $this->feedbackTo()->where('is_positive', true)->count() ?? 0;
        $negatives = $this->feedbackTo()->where('is_positive', false)->count() ?? 0;
        return Attribute::make(get: fn() => $positives - $negatives);
    }

    public function isGivenFeedback(Order|Trade $model): bool
    {
        return $model->feedback()->where('from_user_id', $this->id)->count() > 0;
    }

    public function getSettingByRole(FeedbackRole $role): object
    {
        $levelService = (new LevelService($this))->setRole($role);
        return $levelService->converter->fromLevelToSetting($levelService->getLevel());
    }
}
