<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ScoresController extends Controller
{
    /**
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(User $user, Request $request)
    {
        $request->validate([
            'score' => 'required|integer|max:100|min:1',
            'role'  => 'required|in:shopper,earner'
        ]);

        $user->scores()->create([
            'rate' => $request->input('score'),
            'role' => $request->input('role'),
            'from_user_id' => $this->user->id
        ]);

        $this->info("User score increased.");

        return back();

    }
}
