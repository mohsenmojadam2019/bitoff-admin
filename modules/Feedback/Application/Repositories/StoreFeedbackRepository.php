<?php

namespace Bitoff\Feedback\Application\Repositories;

use App\Models\Order;
use App\Models\User;
use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Models\User as MantisUser;
use Spatie\Activitylog\Models\Activity;
use Throwable;

class StoreFeedbackRepository implements StoreFeedbackRepositoryInterface
{
    private User|MantisUser $fromUser, $toUser;
    private FeedbackRole $role;
    private Order|Trade $feedbackable;
    private Feedback $feedback;

    public function setFromUser(User|MantisUser $user): self
    {
        $this->fromUser = $user;
        return $this;
    }

    public function setToUser(User|MantisUser $user): self
    {
        $this->toUser = $user;
        return $this;
    }

    public function setRole(FeedbackRole $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function setFeedbackable(Order|Trade $feedbackable): self
    {
        $this->feedbackable = $feedbackable;
        return $this;
    }

    /**
     * @param bool $isPositive
     * @param string|null $comment
     * @return Feedback
     * @throws Throwable
     */
    public function store(bool $isPositive = false, string $comment = null): Feedback
    {
        $feedback = new Feedback([
            'role' => $this->role,
            'is_positive' => $isPositive,
            'comment' => $comment,
        ]);

        $feedback->toUser()->associate($this->toUser);
        $feedback->fromUser()->associate($this->fromUser);
        $feedback->feedbackable()->associate($this->feedbackable);
        $feedback->saveOrFail();
        $this->feedback = $feedback;

        $action = $this->feedbackable instanceof Order ? 'actionForOrder' : 'actionForTrade';
        $this->$action($isPositive, $comment);

        return $feedback;
    }

    private function actionForOrder(bool $isPositive = false, string $comment = null)
    {
        /** @var Order $order */
        $order = $this->feedbackable;
        $method = $this->role === FeedbackRole::ROLE_EARNER ? 'storeLogViaEarner' : 'storeLogViaShopper';
        $order->$method($this->fromUser, [
            'type' => 'score',
            'reserve_id' => $order->reserve_id,
            'changes' => [
                'feedback' => $isPositive,
                'comment' => $comment,
            ],
        ]);

        if ($order->isFeedbackDone()) {
            $order->completeAfterFeedbackDone();
        }
    }

    private function actionForTrade(bool $isPositive = false, string $comment = null)
    {
        /** @var Trade $trade */
        $trade = $this->feedbackable;
        $this->logFeedback($trade);

        if ($trade->isFeedbackDone()) {
            $trade->completeAfterFeedbackDone();
        }
    }

    /**
     * Log feedback.
     */
    private function logFeedback(Trade $trade): void
    {
        activity()
            ->performedOn($trade)
            ->withProperties([
                'feedback' => $this->feedback->only([
                    'id',
                    'is_positive',
                    'comment',
                    'to_user_id',
                ]),
            ])
            ->tap(function (Activity $activity) {
                $activity->log_name = Trade::class;
            })
            ->log('feedback');
    }
}
