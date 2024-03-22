<?php

namespace App\Http\Requests\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;

class ForbiddenUsernameRequest extends FormRequest
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
     * @author Reza Sarlak
     * @return array
     */
    public function rules()
    {
        return [
            'username.*'=>['required']
        ];
    }
}
