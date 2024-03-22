<?php

namespace Bitoff\Feedback\Application\Repositories;

use Illuminate\Support\Collection;

interface LevelRepositoryInterface
{
    public function setModuleFilter(): self;
    public function getOrMakeDefault(): Collection;
}
