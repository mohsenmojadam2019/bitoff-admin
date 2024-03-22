<?php

namespace Bitoff\Mantis\Application\Http\Requests\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;

class OffererLevelRequest extends FormRequest
{
    public function rules()
    {
        return [
            'min.*' => ['required'],
            'max.*' => ['required'],
            'level.*' => ['required'],
            'max_offer.*' => ['required'],
            'max_percent.*' => ['required'],
        ];
    }
}
