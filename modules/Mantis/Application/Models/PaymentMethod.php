<?php

namespace Bitoff\Mantis\Application\Models;

use Bitoff\Mantis\Database\Factories\PaymentMethodFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class PaymentMethod extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    public const ACTIVE = true;
    public const INACTIVE = false;

    protected $fillable = [
        'name',
        'active',
        'time',
        'fee',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'parent_id');
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class);
    }

    public function hasCurrency(int $id): bool
    {
        return $this->currencies()->where('id',$id)->exists();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function hasTag(int $id): bool
    {
        return $this->tags()->where('id',$id)->exists();
    }

    protected static function newFactory(): Factory
    {
        return PaymentMethodFactory::new();
    }

    public function isParent(): bool
    {
        return $this->parent_id === null;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')
            ->singleFile();
    }
}
