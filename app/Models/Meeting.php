<?php

namespace App\Models;

use Database\Factories\MeetingFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $video_url
 * @property int $duration_seconds
 * @property array<int, array<string, mixed>>|null $transcript
 * @property string|null $summary
 * @property string $summary_template
 * @property array<int, array<string, mixed>>|null $action_items
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read Collection<int, Highlight> $highlights
 */
class Meeting extends Model
{
    /** @use HasFactory<MeetingFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'video_url',
        'duration_seconds',
        'transcript',
        'summary',
        'summary_template',
        'action_items',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'transcript' => 'array',
            'action_items' => 'array',
        ];
    }

    /**
     * Get the user that owns the meeting.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the highlights for the meeting.
     *
     * @return HasMany<Highlight, $this>
     */
    public function highlights(): HasMany
    {
        return $this->hasMany(Highlight::class)->orderBy('timestamp_seconds');
    }
}
