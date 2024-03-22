<?php

namespace Bitoff\Feedback\Application\Models;

use App\Models\User;
use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Database\Factories\LevelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $level
 * @property FeedbackRole $role
 * @property int $user_id
 * @property-read User $user
 */
class Level extends Model
{
    use HasFactory;

    protected $fillable = ['level', 'role', 'user_id'];
    protected $attributes = [
        'level' => 1
    ];

    protected $casts = [
        'role' => FeedbackRole::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function newFactory(): LevelFactory
    {
        return LevelFactory::new();
    }
}
