<?php

namespace App\Services\Settings;

interface SettingsContract
{
    /**
     * check if key exists.
     *
     * @param $key
     *
     * @return mixed
     */
    public static function has($key);

    /**
     * return value if exists
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public static function get($key, $default = null);

    public static function getAll();

    /**
     * @param array $keys
     *
     * @return array
     */
    public static function getMany(array $keys);

    public static function getManyWithDefault(array $keys, $default);

    /**
     * Store new key
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function set($key, $value);

    /**
     * @param $key
     *
     * @return bool
     */
    public static function unset($key);

    /**
     * set key if it doesn't exist.
     *
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public static function setDefault($key, $value);

    /**
     * Remove key and return the value
     *
     * @param $key
     *
     * @return mixed
     */
    public static function pop($key);

    /**
     * @return array
     */
    public static function keys();
}
