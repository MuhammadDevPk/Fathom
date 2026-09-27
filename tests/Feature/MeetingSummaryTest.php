<?php

use App\Jobs\GenerateMeetingSummary;
use App\Models\Meeting;
use App\Models\User;
use App\Services\GroqClient;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

it('serves a deferred summary prop on meeting detail', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $response = $this->get(route('meetings.show', $meeting));

    $response->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Meetings/Show')
            ->has('meeting')
            ->has('transcript')
            ->loadDeferredProps(fn (AssertableInertia $page) => $page
                ->has('summary')
                ->where('summary', $meeting->getSummaryForTemplate('general'))
            )
        );
});

it('serves tailored template summaries on partial reload', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $manifest = public_path('build/manifest.json');
    $version = file_exists($manifest) ? hash_file('xxh128', $manifest) : null;

    // Partial reload for sales template
    $response = $this->get(route('meetings.show', ['meeting' => $meeting, 'template' => 'sales']), array_filter([
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
        'X-Inertia-Partial-Component' => 'Meetings/Show',
        'X-Inertia-Partial-Data' => 'summary,active_template',
    ]));

    $response->assertOk();
    $data = $response->json();
    expect($data)->toBeArray()
        ->and($data['component'])->toBe('Meetings/Show')
        ->and($data['props']['active_template'])->toBe('sales')
        ->and($data['props']['summary'])->toBe($meeting->getSummaryForTemplate('sales'));
});

it('dispatches GenerateMeetingSummary job and updates summary in database', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create([
        'user_id' => $user->id,
        'transcript' => [
            ['speaker' => 'Alex Chen', 'start' => 0.0, 'end' => 5.0, 'text' => 'Let us review the Q3 pipeline.'],
        ],
        'summary' => json_encode(['general' => 'Initial general summary']),
    ]);

    $mockGroq = Mockery::mock(GroqClient::class);
    $mockGroq->shouldReceive('generateSummary')
        ->once()
        ->with(Mockery::type('array'), 'sales')
        ->andReturn("## Deal Overview & Prospect Sentiment\nAI synthesized sales perspective.");

    $job = new GenerateMeetingSummary($meeting, 'sales');
    $job->handle($mockGroq);

    $fresh = $meeting->fresh();
    expect($fresh)->not->toBeNull()
        ->and($fresh->getSummaryForTemplate('sales'))->toContain('AI synthesized sales perspective.')
        ->and($fresh->getSummaryForTemplate('general'))->toContain('Initial general summary');
});

it('allows dispatching summary generation through controller endpoint', function () {
    Queue::fake();

    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $response = $this->post(route('meetings.summary.generate', $meeting), [
        'template' => 'engineering',
    ]);

    $response->assertRedirect();
    Queue::assertPushed(GenerateMeetingSummary::class, function ($job) use ($meeting) {
        return $job->meeting->id === $meeting->id && $job->template === 'engineering';
    });
});

it('falls back gracefully to seeded summary when LLM generation fails or throws', function () {
    $user = User::factory()->create();
    $seededSummary = '## Seeded Fallback Summary';
    $meeting = Meeting::factory()->create([
        'user_id' => $user->id,
        'summary' => $seededSummary,
    ]);

    $failingGroq = Mockery::mock(GroqClient::class);
    $failingGroq->shouldReceive('generateSummary')
        ->andThrow(new RuntimeException('GROQ_API_KEY is not configured.'));

    $job = new GenerateMeetingSummary($meeting, 'sales');
    $job->handle($failingGroq);

    // Seeded summary remains completely intact
    expect($meeting->fresh()->summary)->toBe($seededSummary);
});
