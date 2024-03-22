<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserBlockRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserBlockController extends Controller
{
    /**
     * @param User $user
     * @param UserBlockRequest $request
     * @return RedirectResponse
     */
    public function update(User $user, UserBlockRequest $request): RedirectResponse
    {
        $method = $request->get('status');

        $user->$method();

        return back();
    }
}
