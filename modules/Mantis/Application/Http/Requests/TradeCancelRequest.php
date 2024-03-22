<?php

namespace Bitoff\Mantis\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TradeCancelRequest extends FormRequest
{
    public function rules()
    {
        return [
            'reason' => ['required', 'string', 'min:1', 'max:250'],
        ];
    }

    public function messages()
    {
        $otherValidationMessage = 'Reason must between 1 to 250 character';

        return [
            'reason.required' => 'Reason is required',
            'reason.string' => $otherValidationMessage,
            'reason.min' => $otherValidationMessage,
            'reason.max' => $otherValidationMessage,
        ];
    }
}
