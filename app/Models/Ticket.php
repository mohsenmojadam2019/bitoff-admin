<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const STATUS = [
        'pending',
        'review',
        'close'
    ];

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @param $newState
     * @param bool $commit
     * @return $this
     */
    protected function to($newState, $commit = true)
    {
        if (in_array($newState, ['pending', 'review', 'close'])) {
            $this->status = $newState;
            $this->status_update = now();
        }

        if ($commit) {
            $this->save();
        }

        return $this;
    }

    /**
     * @param bool $commit
     * @return $this
     */
    public function close($commit = true)
    {
        return $this->to('close', $commit);
    }

    public function review($commit = true)
    {
        return $this->to('review', $commit);
    }

    public function pending($commit = true)
    {
        return $this->to('pending', $commit);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
