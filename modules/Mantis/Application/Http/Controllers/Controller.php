<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * @property User $user
 */
class Controller extends BaseController
{
    public function ajaxResponse($data, $status = JsonResponse::HTTP_OK)
    {
        return response()->json(['data' => $data], $status);
    }

}
