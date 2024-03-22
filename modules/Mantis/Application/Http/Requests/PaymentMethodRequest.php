<?php

namespace Bitoff\Mantis\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class PaymentMethodRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:250'],
            'parent_id' => ['nullable', Rule::when(!$this->isEmptyString('parent_id'), Rule::exists('payment_methods'))],
            'fee' => ['numeric', 'min:0'],
            'time' => ['integer', 'gte:min_time', 'lte:max_time'],
            'min_time' => ['integer', 'min:30'],
            'max_time' => ['integer', 'max:5000'],
            'currencies' => ['array'],
            'currencies.*.id' => ['integer', Rule::exists('currencies')],
            'tags' => ['array'],
            'tags.*.id' => ['integer', Rule::exists('tags')],
            'is_apply_to_children' => ['string', 'in:on,off'],
            'icon' => 'nullable|file|mimes:jpeg,png,svg|max:2048',
        ];
    }
}
