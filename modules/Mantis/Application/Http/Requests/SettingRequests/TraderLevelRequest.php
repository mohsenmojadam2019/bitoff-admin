<?php

namespace Bitoff\Mantis\Application\Http\Requests\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;

class TraderLevelRequest extends FormRequest
{
    public function rules()
    {
        return [
            'min.*' => ['required'],
            'max.*' => ['required'],
            'level.*' => ['required'],
            'max_trade.*' => ['required'],
            'max_tp.*' => ['required'],
        ];
    }
}
