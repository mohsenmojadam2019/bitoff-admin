<?php

namespace App\Http\Requests\SettingRequests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    use CreateFormatSettingRequest;

    protected $typeRequestValidation = [
        'earner_level' => EarnerLevelRequest::class,//
        'fast_release' => FastReleaseRequest::class,
        'forbidden_usernames' => ForbiddenUsernameRequest::class,
        'max_percent' => MaxPercentRequest::class,
        'prime_order' => PrimeOrderRequest::class,
        'score' => ScoreRequest::class,
        'shopper_level' => ShopLevelRequest::class,//
        'transaction_limit' => TransactionLimitRequest::class,
        'wage' => WageRequest::class,
        'other_wage'=>OtherWageRequest::class,
        'usdt_stuff'=>UsdtStuffRequest::class,
    ];

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
        $setting = (request()->route()->parameter('setting'));

        if (array_key_exists($setting->key, $this->typeRequestValidation)) {
            return (new $this->typeRequestValidation[$setting->key])->rules();
        }
    }

    /**
     * @return array
     */
    public function asSetting()
    {
        return $this->formatSettingReq();
    }
}
