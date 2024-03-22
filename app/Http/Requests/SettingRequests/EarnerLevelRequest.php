<?php

namespace App\Http\Requests\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;

class EarnerLevelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'min.*' => ['required'],
            'max.*' => ['required'],
            'level.*' => ['required'],
            'max_order.*' => ['required'],
            'max_tp.*' => ['required'],
            'fast_ratio.*' => ['required'],
            'vip.*' => ['required'],
        ];
    }
}
