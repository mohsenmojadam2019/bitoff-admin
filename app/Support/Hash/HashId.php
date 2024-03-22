<?php


namespace App\Support\Hash;

use Hashids\Hashids;

class HashId
{
    public static $length = 10;
    public static $alphabet = "ABCDEFHIKLOPQRSTUVWXYZ";

    public static function make($key = null)
    {
        return new Hashids($key ?: env('PROJECT_KEY'), static::$length, static::$alphabet);
    }

    public static function decode($hash)
    {
        return static::make()->decode($hash);
    }

    public static function encode($id)
    {
        return static::make()->encode($id);
    }

}
