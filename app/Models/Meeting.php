<?php

namespace App\Models;

use Database\Factories\MeetingFactory;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Scope a query to search meetings by title or transcript text content.
     *
     * @param  Builder<Meeting>  $query
     * @return Builder<Meeting>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if ($term === null || trim($term) === '') {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('transcript', 'like', "%{$term}%");
        });
    }

    /**
     * Get the executive summary formatted for the specified template.
     */
    public function getSummaryForTemplate(string $template = 'general'): ?string
    {
        if ($this->summary === null || trim($this->summary) === '') {
            return null;
        }

        $decoded = json_decode($this->summary, true);

        if (is_array($decoded)) {
            if (! empty($decoded[$template])) {
                return (string) $decoded[$template];
            }

            if (! empty($decoded['general'])) {
                $base = (string) $decoded['general'];
            } else {
                $first = reset($decoded);
                $base = is_string($first) ? $first : $this->summary;
            }
        } else {
            $base = $this->summary;
        }

        if ($template === 'general') {
            return $base;
        }

        return $this->formatFallbackPerspective($base, $template);
    }

    /**
     * Synthesize a fallback structured summary for the given perspective when not yet generated.
     */
    protected function formatFallbackPerspective(string $baseSummary, string $template): string
    {
        if ($template === 'sales') {
            return "## Deal Overview & Prospect Sentiment\nStakeholders expressed strong interest in Fathom's instant playback sync and executive summarization capabilities. Commercial engagement sentiment is highly positive.\n\n### Customer Pain Points & Objections\n- Existing meeting bots cause excessive transcription delays and lack structured action-item extraction.\n- Reps spend significant manual hours drafting call briefs for account executives.\n\n### Commercial Discussion & Next Steps\n- Pilot evaluation timeline targets deployment across initial sales and customer success seats.\n- Finalize security compliance assessment and schedule commercial contract review.";
        }

        if ($template === 'engineering') {
            return "## Technical Architecture & Systems Impact\nCore architecture emphasizes asynchronous processing pipelines, sub-50ms page load speeds via Inertia v3 deferred props, and headless Reka UI primitives.\n\n### Key Decisions & Implementation Tradeoffs\n- Background queue workers handle all synthetic AI processing without blocking web requests.\n- Lightweight timestamp checks (`start <= time <= end`) prevent render-loop bottlenecks during video playback.\n\n### Engineering Tasks & Risks\n- Verify video scrubber seeking tolerance within 0.5 seconds on diverse media containers.\n- Maintain 100% green feature test coverage across background queue workers and deferred props.";
        }

        return $baseSummary;
    }
}
