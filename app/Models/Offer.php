<?php

namespace App\Models;

use Bitoff\Feedback\Application\Models\Feedback;
use MongoDB\Laravel\Eloquent\Model;

class Offer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'offers';

    protected $fillable = [
        'id',
        'product_id',
        'seller_id',
        'price',
        'shipping',
        'condition'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','amazon_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function net()
    {
        return $this->price + $this->shipping;
    }
    public function feedbacks()
    {
        return $this->morphMany(Feedback::class,'feedbackable');
    }
}
