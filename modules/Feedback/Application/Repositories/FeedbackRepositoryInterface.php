<?php

namespace Bitoff\Feedback\Application\Repositories;

interface FeedbackRepositoryInterface
{
    public function setFromOrToFilter($user): self;
    public function setModuleFilter(): self;
    public function setIsPositiveFilter(): self;
    public function setRoleFilter(): self;
    public function setDefaultSorting(): self;
    public function setCreatedAtFilter(): self;
}
