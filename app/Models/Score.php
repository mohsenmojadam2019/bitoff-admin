<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{

    protected $fillable = ['score', 'rate', 'role', 'from_user_id', 'to_user_id'];

    public function from()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }
}
