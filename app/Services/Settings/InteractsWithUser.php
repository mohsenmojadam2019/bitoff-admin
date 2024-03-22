<?php


namespace App\Services\Settings;

use App\Models\User;

trait InteractsWithUser
{
    /**
     * @param User $shopper
     *
     * @return object
     */
    public static function shopper(User $shopper)
    {
        return static::findByScore($shopper->shopScore, static::get('shopper_level'));
    }

    /**
     * @param User $earner
     *
     * @return object
     */
    public static function earner(User $earner)
    {
        return static::findByScore($earner->earnScore, static::get('earner_level'));
    }

    /**
     * @param $score
     * @param object | \Illuminate\Support\Collection $config
     *
     * @return object
     */
    public static function findByScore($score, $config)
    {
        if ($score < 0) {
            $level = $config->sortBy('min')->first();
        } else {
            $level = $config->where('max', '>=', $score)
                ->where('min', '<=', $score)
                ->first();
        }

        return $level ?: $config->sortByDesc('min')->first();
    }
}
