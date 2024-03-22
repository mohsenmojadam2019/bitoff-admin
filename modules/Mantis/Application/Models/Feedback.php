<?php

namespace Bitoff\Mantis\Application\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'is_positive',
        'comment',
    ];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function from(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'from_user_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'to_user_id');
    }
}
