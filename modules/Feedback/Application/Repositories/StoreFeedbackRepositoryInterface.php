<?php

namespace Bitoff\Feedback\Application\Repositories;

use App\Models\Order;
use App\Models\User;
use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Models\User as MantisUser;

interface StoreFeedbackRepositoryInterface
{
    public function setFromUser(User|MantisUser $user): self;
    public function setToUser(User|MantisUser $user): self;
    public function setRole(FeedbackRole $role): self;
    public function setFeedbackable(Order|Trade $feedbackable): self;
    public function store(bool $isPositive = false, string $comment = null): Feedback;
}
