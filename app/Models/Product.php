<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';
    protected $guarded = ['_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getPriceAttribute()
    {
        return (object) $this->attributes['price'];
    }

    public function offers()
    {
        return $this->hasMany(Offer::class,'product_id','amazon_id');
    }

    public function getIndexSelectedVariation($index)
    {
        $currentAin =  $this->variations['currentAsin'];

        return $this->variations['asinToDimensionIndexMap'][$currentAin][$index];
    }
}
