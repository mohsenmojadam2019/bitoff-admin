<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    const FROM_AMAZON = 'amazon_website';
    const FROM_OTHER = 'other_website';
    protected $dates = ['last_inception'];

    public function items()
    {
        return $this->hasMany(TrackItem::class);
    }

    public function getPackage()
    {
        return $this->items->filter(function ($item) {
            return !!$item->payload['tracking'];
        })->first();
    }

}
