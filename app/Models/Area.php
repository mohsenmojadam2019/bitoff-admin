<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'city',
        'county',
        'latitude',
        'longitude',
        'state',
        'state_iso',
        'zip_code',
    ];
}
