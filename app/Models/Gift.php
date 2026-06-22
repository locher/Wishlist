<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $profile_id
 * @property string $title
 * @property string|null $description
 * @property string|null $link
 * @property bool $is_list
 * @property int|null $reserved_by_profile_id
 * @property string|null $reserved_by_guest_name
 * @property Carbon|null $reserved_at
 * @property-read bool $is_reserved
 * @property-read Profile $profile
 * @property-read Profile|null $reservedByProfile
 */
class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'title',
        'description',
        'link',
        'is_list',
        'reserved_by_profile_id',
        'reserved_by_guest_name',
        'reserved_at',
    ];

    protected function casts(): array
    {
        return [
            'is_list' => 'boolean',
            'reserved_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Profile, $this>
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * @return BelongsTo<Profile, $this>
     */
    public function reservedByProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'reserved_by_profile_id');
    }

    public function getIsReservedAttribute(): bool
    {
        return filled($this->reserved_by_profile_id) || filled($this->reserved_by_guest_name);
    }
}
