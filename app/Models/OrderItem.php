<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    const STATUS_INIT = 'init';
    const STATUS_PURCHASE = 'purchase';
    const STATUS_SHIP = 'ship';
    const STATUS_DELIVER = 'deliver';
    const STATUS_CANCEL = 'cancel';
}
