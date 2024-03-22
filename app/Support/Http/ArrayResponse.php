<?php

namespace App\Support\Http;


class ArrayResponse implements HttpResponseInterface
{

    /**
     * @param $data
     * @return false|string
     */
    public function getContent($data)
    {
        return json_decode($data, true);
    }
}
