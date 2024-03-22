<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'body',
        'admin',
        'user_id'
    ];

    protected $casts = [
        'admin' => 'boolean'
    ];

    protected $table = 'ticket_replies';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

}
