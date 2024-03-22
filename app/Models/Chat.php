<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Chat extends Model
{
    protected $collection = 'chats';
    protected $connection = 'mongodb';

    protected $guarded = [];

}
