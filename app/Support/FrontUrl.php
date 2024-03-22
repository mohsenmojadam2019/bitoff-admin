<?php


namespace App\Support;

class FrontUrl
{
    public static function base()
    {
        return env('FRONTEND_ADDRESS');
    }

    public static function product($id, $absolute = true)
    {
        if (!$absolute) {
            return sprintf('/product/%s', $id);
        }

        return sprintf('%s/product/%s', static::base(), $id);
    }

    public static function item($id, $item)
    {
        return sprintf('%s#%s', static::order($id), $item);
    }

    public static function order($id)
    {
        return sprintf('%s/p/order/%s', static::base(), $id);
    }

    public static function ticket($id)
    {
        return sprintf('%s/p/ticket/%d', static::base(), $id);
    }
}
