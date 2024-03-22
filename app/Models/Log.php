<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'order_logs';
    protected $guarded = ['id'];

    protected $casts = [
        'changes' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function imageThumbnail($id)
    {
        $reservation = ReservationImage::query()->find($id);

        if ($reservation) {
            return [
                'order_id' => $this->order->hash,
                'path' => $reservation->thumbnail()
            ];
        }
        return false;
    }

    public function reserve()
    {
        return $this->belongsTo(Reservation::class, 'reserve_id');
    }
}
