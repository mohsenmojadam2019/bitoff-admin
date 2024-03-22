<?php

namespace Bitoff\Mantis\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
        ];
    }
}
