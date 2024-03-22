<?php

namespace Bitoff\Feedback\Application\Models;

use Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['key', 'value'];
    protected $casts = ['value' => 'collection'];
    protected $primaryKey = 'key';
    protected $table = 'settings';

    const CACHE_EXPIRE_TIME = 120;

    public static function getAll(): Collection
    {
        return Cache::remember('settings', self::CACHE_EXPIRE_TIME, fn() => static::all());
    }
}
