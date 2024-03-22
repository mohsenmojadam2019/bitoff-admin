<?php

namespace Bitoff\Feedback\Application\Repositories;

use App\Models\Order;
use Bitoff\Mantis\Application\Models\Trade;

class FeedbackRepository extends BaseRepository implements FeedbackRepositoryInterface
{
    public function setFromOrToFilter($user): self
    {
        $from = $this->request->get('from', 'all');

        if ($from === 'me_to_other') {
            $this->query->where('from_user_id', $user->id);
        } elseif ($from === 'other_to_me') {
            $this->query->where('to_user_id', $user->id);
        } else {
            $this->query->where(fn($q) => $q
                ->where('from_user_id', $user->id)
                ->orWhere('to_user_id', $user->id));
        }

        return $this;
    }

    public function setModuleFilter(): self
    {
        $this->query->when($this->request->filled('module'), fn($q) => $q
            ->where('feedbackable_type', $this->request->get('module') === 'trade' ? Trade::class : Order::class));
        return $this;
    }

    public function setIsPositiveFilter(): self
    {
        $this->query->when($this->request->filled('is_positive'), fn($q) => $q
            ->where('is_positive', (boolean) $this->request->get('is_positive')));
        return $this;
    }

    public function setRoleFilter(): self
    {
        $this->query->when($this->request->filled('role'), fn($q) => $q
            ->where('role', $this->request->get('role')));
        return $this;
    }

    public function setDefaultSorting(): self
    {
        $this->query->orderByDesc('created_at');
        return $this;
    }

    public function setCreatedAtFilter(): self
    {
        $this->query->when($this->request->has('created_at'), fn($q) => $q
            ->where('created_at', '>=', now()->subDays($this->request->getTimeAsDays())));
        return $this;
    }
}
