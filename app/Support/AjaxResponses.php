<?php

namespace App\Support;

use Illuminate\Http\Response;

trait AjaxResponses
{

    /**
     * @param $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($message = null, $status = Response::HTTP_OK)
    {
        $response = [];
        if ($message) {
            $response['message'] = $message;
        }
        return response()->json($response, $status);
    }

}
