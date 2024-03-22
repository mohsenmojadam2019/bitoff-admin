<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'btc'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'reservation_track', 'reserve_id')
            ->latest('last_inception');
    }

    /**
     * @return MorphMany
     */
    public function credits()
    {
        return $this->morphMany(Credit::class, 'creditable')->latest();
    }

    /**
     * @param array $data
     *
     * @return Credit|Model
     */
    public function storeCredit(array $data)
    {
        return $this->credits()->create($data);
    }

    /**
     * @param User $user
     * @param array $data
     *
     * @return Credit|Model
     */
    public function creditFor(User $user, array $data)
    {
        return $this->storeCredit(array_merge($data, ['user_id' => $user->id]));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'reserve_id');
    }

    public function storeLog(array $data)
    {
        return $this->logs()->create(array_merge($data, [
            'order_id' => $this->order_id,
            'user_id'  => $this->user_id,
        ]));
    }

    /**
     * Relation by reservation image
     *
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(ReservationImage::class,'reserve_id');
    }

}
