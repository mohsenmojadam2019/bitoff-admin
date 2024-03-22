<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackItem extends Model
{

    protected $casts = ['payload' => 'array'];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

}
