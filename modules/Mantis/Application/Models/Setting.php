<?php

namespace Bitoff\Mantis\Application\Models;

use Bitoff\Mantis\Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model 
{    
    use HasFactory;

    protected $table = 'settings';

    public const OFFERER_LEVEL = 'offerer_level';
    public const TRADER_LEVEL = 'trader_level';

    public static function settings(): array
    {
        return [
            self::OFFERER_LEVEL,
            self::TRADER_LEVEL,
        ];
    }
    
    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'object'];

    public $incrementing = false;

    protected $primaryKey = 'key';

    public function getRouteKey()
    {
        return 'key';
    }

    protected static function newFactory(): Factory
    {
        return SettingFactory::new();
    }
}
