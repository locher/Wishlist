<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_child
 * @property int $avatar
 * @property Carbon|null $birthday
 * @property string|null $size_top
 * @property string|null $size_bottom
 * @property string|null $size_feet
 * @property-read string $avatar_url
 * @property-read string|null $display_birthday
 * @property-read string|null $display_age
 */
class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_child',
        'avatar',
        'birthday',
        'size_top',
        'size_bottom',
        'size_feet',
    ];

    protected function casts(): array
    {
        return [
            'is_child' => 'boolean',
            'birthday' => 'date',
        ];
    }

    /**
     * @return HasMany<Gift, $this>
     */
    public function gifts(): HasMany
    {
        return $this->hasMany(Gift::class);
    }

    /**
     * @return BelongsToMany<Profile, $this>
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'profile_relations', 'child_id', 'parent_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Profile, $this>
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'profile_relations', 'parent_id', 'child_id')
            ->withTimestamps();
    }

    public function getAvatarUrlAttribute(): string
    {
        return asset("images/avatar/avatar{$this->avatar}.png");
    }

    public function getDisplayBirthdayAttribute(): ?string
    {
        return $this->birthday?->translatedFormat('j F Y');
    }

    public function getDisplayAgeAttribute(): ?string
    {
        if (! $this->birthday instanceof Carbon) {
            return null;
        }

        $years = $this->birthday->age;

        if ($years <= 2) {
            return trans_choice('messages.age.months', (int) $this->birthday->diffInMonths(now()), [
                'count' => (int) $this->birthday->diffInMonths(now()),
            ]);
        }

        return trans_choice('messages.age.years', $years, [
            'count' => $years,
        ]);
    }
}
