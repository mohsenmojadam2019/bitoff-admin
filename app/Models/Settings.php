<?php

namespace App\Models;

use App\Support\Settings\SettingsContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Settings extends Model implements SettingsContract
{
    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'object'];

    public $incrementing = false;

    protected $primaryKey = 'key';

    public function getRouteKey()
    {
        return 'key';
    }

    public static function settings(): array
    {
        return [
            'earner_level',//
            'fast_release',
            'forbidden_usernames',
            'max_percent',
            'prime_order',
            'score',//
            'shopper_level',//
            'transaction_limit',
            'wage',
            'other_wage',
            'usdt_stuff',
        ];
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public static function key($key)
    {
        $setting = static::where(compact('key'))->first();
        if ($setting) {
            return $setting;
        }

        throw new \Exception("{$key} does not exist in settings.");
    }

    /**
     * check if key exists.
     * @param $key
     * @return mixed
     */
    public static function has($key)
    {
        return static::where(compact('key'))->exists();
    }

    /**
     * return value if exists
     * @param $key
     * @param $default
     * @return object | \Illuminate\Support\Collection | mixed
     */
    public static function get($key, $default = null)
    {
        try {
            $settings = static::key($key);
            foreach ($settings->value as $item) {
                if (is_object($item)) {
                    return collect($settings->value);
                }
            }

            return $settings->value;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        $result = [];

        foreach (static::all() as $setting) {
            $result[$setting->key] = $setting->value;
        }

        return $result;
    }

    /**
     * @param array $keys
     * @return array
     */
    public static function getMany(array $keys)
    {
        return array_map(function ($setting) {
            return $setting['value'];
        }, static::whereIn('key', $keys)->get()->toArray());
    }

    public static function getManyWithDefault(array $keys, $default)
    {
    }

    /**
     * Store new key
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        try {
            static::create(compact('key', 'value'));
        } catch (QueryException $qe) {
            static::where(compact('key'))->update(compact('value'));
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function unset($key)
    {
        if ($setting = static::get($key)) {
            $setting->delete();
            return true;
        }

        return false;
    }

    /**
     * set key if it doesn't exist.
     * @param $key
     * @param $value
     * @return boolean
     */
    public static function setDefault($key, $value)
    {
        if (!static::has($key)) {
            static::set($key, $value);
            return true;
        }

        return false;
    }

    /**
     * @param $setting
     * @param $key
     * @param $value
     * @return bool
     */
    public static function addToValue($setting, $key, $value)
    {
        try {
            $setting = static::key($setting);
            $settings = $setting->value;
            $settings->$key = $value;
            $setting->value = $settings;
            $setting->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Remove key and return the value
     * @param $key
     * @return mixed
     */
    public static function pop($key)
    {
        try {
            $setting = static::key($key);
            $setting->delete();
            return $setting->value;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return array
     */
    public static function keys()
    {
        return static::select('key')->get()->pluck('key')->toArray();
    }

    /**
     * @return array
     */
    public static function values()
    {
        return static::select('value')->get()->pluck('value')->toArray();
    }

    public static function cache()
    {
        foreach (static::getAll() as $key => $value) {
            cache()->forever("settings.{$key}", $value);
        }
    }
}
