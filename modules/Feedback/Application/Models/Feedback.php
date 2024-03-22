<?php

namespace Bitoff\Feedback\Application\Models;

use App\Models\Order;
use App\Models\User;
use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Database\Factories\FeedbackFactory;
use Bitoff\Mantis\Application\Models\Trade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property boolean $is_positive
 * @property FeedbackRole $role
 * @property string $comment
 * @property int $from_user_id
 * @property int $to_user_id
 * @property string $feedbackable_type
 * @property int $feedbackable_id
 * @property-read User $toUser
 * @property-read User $fromUser
 * @property-read Order|Trade $feedbackable
 */
class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'is_positive',
        'role',
        'from_user_id',
        'to_user_id',
        'comment'
    ];

    protected $casts = [
        'is_positive' => 'boolean',
        'role' => FeedbackRole::class
    ];

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function feedbackable(): MorphTo
    {
        return $this->morphTo();
    }
    public function scopeCountPositiveAndNegativeToUser(Builder $builder): Builder
    {
        return $builder
            ->selectRaw("(SUM(is_positive = 1) - SUM(is_positive = 0)) AS feedback_score_sum")
            ->groupBy('to_user_id');
    }

    protected static function newFactory(): FeedbackFactory
    {
        return FeedbackFactory::new();
    }

}
