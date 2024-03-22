<?php

namespace Bitoff\Mantis\Application\Http\Requests\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    use CreateFormatSettingRequest;

    protected $typeRequestValidation = [
        'trader_level' => TraderLevelRequest::class,
        'offerer_level' => OffererLevelRequest::class,
    ];


    public function rules(): array
    {
        $setting = (request()->route()->parameter('setting'));

        if (array_key_exists($setting->key, $this->typeRequestValidation)) {
            return (new $this->typeRequestValidation[$setting->key])->rules();
        }
    }

    public function asSetting(): array
    {
        return $this->formatSettingRequest();
    }
}
