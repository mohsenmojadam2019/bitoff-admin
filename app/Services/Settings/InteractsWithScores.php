<?php


namespace App\Services\Settings;

trait InteractsWithScores
{
    /**
     * @param int $stars
     * @param int $default
     *
     * @return int
     */
    public static function score(int $stars, $default = 0)
    {
        foreach (static::get('score') as $label => $score) {
            if ("star_{$stars}" == $label) {
                return $score;
            }
        }

        return $default;
    }
}
