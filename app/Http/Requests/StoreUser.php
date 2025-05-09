<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['unique:users,username'],
            'password' => ['required', 'min:8'],
            'first_name' => ['string'],
            'last_name' => ['string'],
            'mobile' => ['string'],
            'active' => ['string'],
            'admin' => ['string'],
        ];
    }
}
