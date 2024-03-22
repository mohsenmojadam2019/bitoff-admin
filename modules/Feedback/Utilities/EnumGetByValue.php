<?php

namespace Bitoff\Feedback\Utilities;

trait EnumGetByValue
{
    public static function get(string $value): self
    {
        return collect(self::cases())->firstWhere('value', $value);
    }
}
