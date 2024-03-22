<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\AjaxResponses;
use App\Support\FlashMessages;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * @property User $user
 */
class Controller extends BaseController
{
    use AuthorizesRequests,
    DispatchesJobs,
    ValidatesRequests,
    FlashMessages,
        AjaxResponses;

    protected $__user;

    /**
     * @return User|null
     */
    public function user()
    {
        if (!$this->__user instanceof User && auth()->check()) {
            $this->__user = auth()->user();
        }

        return $this->__user;
    }

    public function __get($name)
    {
        if ($name === 'user') {
            return $this->user();
        }
    }

    public function ajaxResponse($data, $status = JsonResponse::HTTP_OK)
    {
        return response()->json(['data' => $data], $status);
    }

}
