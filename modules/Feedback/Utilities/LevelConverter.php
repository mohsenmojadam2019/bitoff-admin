<?php

namespace Bitoff\Feedback\Utilities;

use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Level;
use Bitoff\Feedback\Application\Models\Settings;
use Illuminate\Support\Collection;

class LevelConverter
{
    private \Illuminate\Database\Eloquent\Collection $settings;
    private FeedbackRole $role;

    public function __construct()
    {
        FeedbackRole::get('shopper');
        $this->settings = Settings::getAll();
    }

    public function fromFeedbackCountToLevel(int|float $count): int
    {
        $sortedSettings = $this->getSetting()->sortByDesc('min');
        $level = $sortedSettings->last();

        foreach ($sortedSettings as $setting) {
            if ($count >= $setting['min']) {
                $level = $setting;
                break;
            }
        }

        return (int) $level['level'];
    }

    public function fromLevelToSetting(int|Level $level): object
    {
        if ($level instanceof Level) {
            $level = $level->level;
        }

        return (object) $this->getSetting()->firstWhere('level', $level);
    }

    public function setRole(FeedbackRole $role)
    {
        $this->role = $role;
        return $this;
    }

    public function getSetting(): Collection
    {
        return $this->settings->firstWhere('key', $this->role->value . "_level")->value;
    }
}
