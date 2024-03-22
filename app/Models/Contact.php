<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Contact
 *
 * @property $address
 * @property $created_at
 * @property $updated_at
 */
class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'message',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
