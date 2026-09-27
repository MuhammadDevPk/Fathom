<?php

use App\Models\Highlight;
use App\Models\Meeting;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('casts transcript and action items to array on Meeting model', function () {
    $meeting = Meeting::factory()->create([
        'title' => 'Sprint Planning & Architecture Sync',
        'transcript' => [
            ['speaker' => 'Alex Chen', 'start' => 0.0, 'end' => 3.5, 'text' => 'Let us begin the review.'],
        ],
        'action_items' => [
            ['id' => 1, 'task' => 'Write Pest feature tests', 'assignee' => 'Alex Chen', 'completed' => false],
        ],
    ]);

    expect($meeting->transcript)->toBeArray()
        ->and($meeting->transcript[0]['speaker'])->toBe('Alex Chen')
        ->and($meeting->transcript[0]['text'])->toBe('Let us begin the review.')
        ->and($meeting->action_items)->toBeArray()
        ->and($meeting->action_items[0]['task'])->toBe('Write Pest feature tests');
});

it('verifies Meeting hasMany Highlights relationship works', function () {
    $meeting = Meeting::factory()->create();

    $highlight = Highlight::factory()->create([
        'meeting_id' => $meeting->id,
        'timestamp_seconds' => 45,
        'label' => 'Architecture Decision',
        'note' => 'Standardized on Reka UI accessible primitives',
    ]);

    expect($meeting->highlights)->toHaveCount(1)
        ->and($meeting->highlights->first()->id)->toBe($highlight->id)
        ->and($meeting->highlights->first()->label)->toBe('Architecture Decision')
        ->and($highlight->meeting->id)->toBe($meeting->id);
});

it('seeds exactly 5 realistic meetings with non-empty transcripts', function () {
    $this->seed(MeetingSeeder::class);

    expect(Meeting::count())->toBe(5)
        ->and(Highlight::count())->toBeGreaterThanOrEqual(15);

    $meetings = Meeting::with('highlights')->get();

    foreach ($meetings as $meeting) {
        expect($meeting->transcript)->toBeArray()
            ->and(count($meeting->transcript))->toBeGreaterThanOrEqual(8)
            ->and($meeting->transcript[0])->toHaveKeys(['speaker', 'start', 'end', 'text'])
            ->and($meeting->summary)->not->toBeEmpty()
            ->and($meeting->action_items)->toBeArray()
            ->and($meeting->action_items)->not->toBeEmpty()
            ->and($meeting->highlights)->not->toBeEmpty();
    }
});
