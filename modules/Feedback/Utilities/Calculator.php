<?php

namespace Bitoff\Feedback\Utilities;

class Calculator
{
    /**
     * @param int $percent
     * @param $number
     *
     * @return float|int
     */
    public static function percent($percent, $number)
    {
        return ($percent / 100) * $number;
    }
}
