<?php

namespace Bitoff\Feedback\Application\Repositories;

use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Services\LevelService;
use Illuminate\Support\Collection;

class LevelRepository extends BaseRepository implements LevelRepositoryInterface
{
    private array $moduleRoleSelect;

    public function setModuleFilter(): LevelRepositoryInterface
    {
        $moduleRoleSelect = [FeedbackRole::ROLE_SHOPPER, FeedbackRole::ROLE_EARNER];

        if ($this->request->get('module') === 'trade') {
            $moduleRoleSelect = [FeedbackRole::ROLE_TRADER, FeedbackRole::ROLE_OFFERER];
        }

        $this->moduleRoleSelect = $moduleRoleSelect;

        return $this;
    }

    public function getOrMakeDefault(): Collection
    {
        $userLevels = collect();

        $levelService = new LevelService($this->request->user());

        foreach ($this->moduleRoleSelect as $role) {
            $userLevels->push($levelService->setRole($role)->getLevel());
        }

        return $userLevels;
    }
}
