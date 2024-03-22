<?php

namespace App\Support\Http;


class JsonResponse implements HttpResponseInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getContent($data)
    {
        return json_decode($data);
    }
}
