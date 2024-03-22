<?php

namespace Bitoff\Feedback\Application\Policies;

use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User;

class FeedbackPolicy
{
    use HandlesAuthorization;

    public function store(User $user, Order $order): Response
    {
        if ($user->is($order->shopper) || $user->is($order->earner)) {
            return $this->allow();
        }

        $this->deny('You can not feedback other users order.');
    }

    protected function deny($message = 'This action is unauthorized also')
    {
        throw new AuthorizationException($message);
    }
}
