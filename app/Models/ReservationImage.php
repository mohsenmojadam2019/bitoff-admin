<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReservationImage extends Model
{
    protected $appends = ['image_url'];


    public function getImageUrlAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }


    public function thumbnail()
    {
        list($path, $extension) = explode('.', $this->path);
        return Storage::disk('public')->url($path . '.th.' . $extension);
    }

}
