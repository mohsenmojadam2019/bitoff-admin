<?php

namespace Bitoff\Feedback\Application\Services;

use App\Models\User;
use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Application\Models\Level;
use Bitoff\Feedback\Utilities\LevelConverter;
use Bitoff\Mantis\Application\Models\User as UserMantis;
use Throwable;

class LevelService
{
    private User|UserMantis $user;
    public LevelConverter $converter;
    private FeedbackRole $role;
    private Level $level;

    public function __construct(User|UserMantis $user)
    {
        $this->user = $user;
        $this->converter = resolve(LevelConverter::class);
    }

    /**
     * Call when new feedback stored
     *
     * @param Feedback $feedback
     * @return bool
     * @throws Throwable
     */
    public static function handleNewFeedback(Feedback $feedback): bool
    {
        $toUser = $feedback->toUser;
        $service = (new static($toUser))->setRole($feedback->role);
        $currentLevel = $service->getLevel()->level;
        $newLevel = $service->calculatedLevel();

        if ($newLevel <= $currentLevel) {
            return true;
        }

        return $service->updateLevel($newLevel);
    }

    /**
     * Set role
     * !warning : this is requirement and very important
     *
     * @param FeedbackRole $role
     * @return $this
     */
    public function setRole(FeedbackRole $role): self
    {
        $this->role = $role;
        $this->converter->setRole($role);
        return $this;
    }

    /**
     * Get current user level
     *
     * @return Level
     */
    public function getLevel(): Level
    {
        /** @var Level $level */
        $level = $this->user->level()->where('role', $this->role)?->first() ?? null;

        if (!isset($level)) {
            $level = $this->user->level()->create(['role' => $this->role]);
        }

        $this->level = $level;
        return $level;
    }

    /**
     * Calculate level with feedback count
     *
     * @return int
     */
    public function calculatedLevel(): int
    {
        return $this->converter->fromFeedbackCountToLevel($this->user->feedbackCount);
    }

    /**
     * Update level
     *
     * @param int $newLevel
     * @return bool
     * @throws Throwable
     */
    public function updateLevel(int $newLevel = 1): bool
    {
        return $this->level->updateOrFail(['level' => $newLevel]);
    }
}
