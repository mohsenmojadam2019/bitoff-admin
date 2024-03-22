<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Seller extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sellers';

    protected $fillable = [
        'id',
        'name',
        'review',
        'prime',
        'delivery',
        'rating'
    ];
}
