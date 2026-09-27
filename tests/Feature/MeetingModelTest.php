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

it('seeds realistic meetings with non-empty transcripts', function () {
    $this->seed(MeetingSeeder::class);

    expect(Meeting::count())->toBe(3)
        ->and(Highlight::count())->toBeGreaterThanOrEqual(9);

    $meetings = Meeting::with('highlights')->get();

    foreach ($meetings as $meeting) {
        expect($meeting->transcript)->toBeArray()
            ->and(count($meeting->transcript))->toBeGreaterThanOrEqual(3)
            ->and($meeting->transcript[0])->toHaveKeys(['speaker', 'start', 'end', 'text'])
            ->and($meeting->summary)->not->toBeEmpty()
            ->and($meeting->action_items)->toBeArray()
            ->and($meeting->action_items)->not->toBeEmpty()
            ->and($meeting->highlights)->not->toBeEmpty();
    }
});

it('factory produces a meeting with a well-formed transcript', function () {
    $meeting = Meeting::factory()->create();

    expect($meeting->transcript)->toBeArray()->not->toBeEmpty();
    expect($meeting->transcript[0])->toHaveKeys(['start', 'end', 'speaker', 'text']);
    expect($meeting->action_items)->toBeArray()->not->toBeEmpty();
    expect($meeting->action_items[0])->toHaveKeys(['id', 'task', 'assignee', 'completed']);

    $decodedSummary = json_decode((string) $meeting->summary, true);
    expect($decodedSummary)->toBeArray()
        ->toHaveKeys(['general', 'sales', 'engineering']);
});
