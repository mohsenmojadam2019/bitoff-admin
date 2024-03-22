<?php

namespace Bitoff\Mantis\Application\Models;

use Bitoff\Mantis\Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class);
    }

    protected static function newFactory(): Factory
    {
        return TagFactory::new();
    }
}
