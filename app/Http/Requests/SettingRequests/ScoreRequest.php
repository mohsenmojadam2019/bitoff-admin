<?php

namespace App\Http\Requests\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;

class ScoreRequest extends FormRequest
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
            'star_1.*'=>['required'],
            'star_2.*'=>['required'],
            'star_3.*'=>['required'],
            'star_4.*'=>['required'],
            'star_5.*'=>['required'],
        ];
    }
}
