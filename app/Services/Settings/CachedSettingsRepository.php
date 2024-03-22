<?php

namespace App\Services\Settings;

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class CachedSettingsRepository implements SettingsContract
{
    use InteractsWithUser, InteractsWithScores;

    const KEY = 'settings';

    /**
     * @param $key
     *
     * @return string
     */
    protected static function cacheName($key)
    {
        return self::KEY.'.'.$key;
    }
    /**
     * check if key exists.
     *
     * @param $key
     *
     * @return mixed
     */
    public static function has($key)
    {
        return Cache::has(static::cacheName($key));
    }

    /**
     * return value if exists
     *
     * @param $key
     * @param $default
     *
     * @return object | \Illuminate\Support\Collection | mixed
     */
    public static function get($key, $default = null)
    {
        $settings = Cache::rememberForever(static::cacheName($key), function () use ($key) {
            return Settings::get($key);
        });

        foreach ($settings as $item) {
            if (is_object($item)) {
                return collect($settings);
            }
        }

        return $settings;
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        return Settings::getAll();
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public static function getMany(array $keys)
    {
        $settings = [];
        foreach ($keys as $key) {
            if ($setting = static::get($key, false)) {
                $settings [] = $setting;
            }
        }

        return $settings;
    }

    /**
     * @param array $keys
     * @param $default
     *
     * @return array
     */
    public static function getManyWithDefault(array $keys, $default)
    {
        $settings = [];
        foreach ($keys as $key) {
            if ($setting = static::get($key, false)) {
                $settings [] = $setting;
            } else {
                $settings [] = $default;
            }
        }

        return $settings;
    }

    /**
     * Store new key
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public static function set($key, $value)
    {
        Cache::forever(static::cacheName($key), $value);
    }

    /**
     * @param $key
     *
     * @return void
     */
    public static function unset($key)
    {
        Cache::forget(static::cacheName($key));
    }

    /**
     * set key if it doesn't exist.
     *
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public static function setDefault($key, $value)
    {
        if (!Cache::has(static::cacheName($key))) {
            static::set($key, $value);

            return true;
        }

        return false;
    }

    /**
     * Remove key and return the value
     *
     * @param $key
     *
     * @return mixed
     */
    public static function pop($key)
    {
        $setting = static::get($key, false);

        if ($setting) {
            self::unset($key);

            return $setting;
        }

        return null;
    }

    /**
     * @return array
     */
    public static function keys()
    {
        return Settings::keys();
    }

    /**
     *  Read all items from db and insert in cache.
     */
    public static function sync()
    {
        foreach (Settings::getAll() as $key => $value) {
            static::set($key, $value);
        }
    }

    /**
     * Read all items from db and insert in cache if it doesn't exist in cache.
     */
    public static function syncDefaults()
    {
        foreach (Settings::getAll() as $key => $value) {
            static::setDefault($key, $value);
        }
    }
}
