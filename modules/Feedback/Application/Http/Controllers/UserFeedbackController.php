<?php

namespace Bitoff\Feedback\Application\Http\Controllers;

use App\Grid\Users\FeedBackGrid;
use App\Http\Controllers\UserReportController;
use App\Models\User;
use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Application\Models\Level;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use SrkGrid\GridView\Grid;

class UserFeedbackController extends UserReportController
{

    private function countPositiveAndNegative($user): array
    {
        $userFeedbackTo = $user->feedbackTo ?? collect();

        $shopperCollection = $userFeedbackTo->where('role', FeedbackRole::ROLE_SHOPPER);
        $earnerCollection = $userFeedbackTo->where('role', FeedbackRole::ROLE_EARNER);
        $offererCollection = $userFeedbackTo->where('role', FeedbackRole::ROLE_OFFERER);
        $traderCollection = $userFeedbackTo->where('role', FeedbackRole::ROLE_TRADER);

        return [
            $shopperCollection,
            $earnerCollection,
            $offererCollection,
            $traderCollection,
        ];
    }

    private function userLevelFetch($user): array
    {
        $userFeedbackTo = $user->feedbackTo ?? collect();

        $shopperLevel = $user->getSettingByRole(FeedbackRole::ROLE_SHOPPER)->level;
        $earnerLevel = $user->getSettingByRole(FeedbackRole::ROLE_EARNER)->level;
        $offererLevel = $user->getSettingByRole(FeedbackRole::ROLE_OFFERER)->level;
        $traderLevel = $user->getSettingByRole(FeedbackRole::ROLE_TRADER)->level;

        return [
            $shopperLevel,
            $earnerLevel,
            $offererLevel,
            $traderLevel,
        ];
    }

    protected function showFeedback($view, $request, $baseRoute, $data, $links)
    {
        $user = User::findOrFail($request->id);

        list($shopper, $earner, $offerer, $trader) = $this->countPositiveAndNegative($user);
        list($shopperLevel, $earnerLevel, $offererLevel, $traderLevel) = $this->userLevelFetch($user);

        $roles = Role::all();

        return view('users.report', compact(
            'shopper',
            'earner',
            'offerer',
            'trader',
            'view',
            'user',
            'baseRoute',
            'links',
            'data',
            'roles',
            'shopperLevel',
            'earnerLevel',
            'offererLevel',
            'traderLevel',
        ));
    }


}
