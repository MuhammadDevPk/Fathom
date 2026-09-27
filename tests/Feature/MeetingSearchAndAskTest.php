<?php

use App\Models\Meeting;
use App\Models\User;
use App\Services\GroqClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

it('answers a meeting question using transcript context', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create([
        'user_id' => $user->id,
        'title' => 'Product Architecture Alignment',
        'transcript' => [
            ['speaker' => 'Alex Chen', 'start' => 45.0, 'end' => 55.0, 'text' => 'We are standardizing on Reka UI primitives.'],
        ],
        'summary' => json_encode(['general' => 'Standardized on Reka UI primitives for accessible components.']),
    ]);

    $mockGroq = Mockery::mock(GroqClient::class);
    $mockGroq->shouldReceive('answerQuestion')
        ->once()
        ->with(
            Mockery::on(function ($context) {
                return isset($context['transcript']) && isset($context['summary']);
            }),
            'What UI library was chosen?'
        )
        ->andReturn('At [00:45], Alex Chen confirmed the team standardized on Reka UI primitives.');

    $this->app->instance(GroqClient::class, $mockGroq);

    $response = $this->actingAs($user)
        ->post(route('meetings.ask', $meeting), [
            'question' => 'What UI library was chosen?',
        ]);

    $response->assertRedirect();

    $qa = session("meeting_{$meeting->id}_qa");
    expect($qa)->toBeArray()->toHaveCount(1)
        ->and($qa[0]['question'])->toBe('What UI library was chosen?')
        ->and($qa[0]['answer'])->toContain('At [00:45], Alex Chen confirmed');
});

it('filters meetings by search term', function () {
    $user = User::factory()->create();
    $meeting1 = Meeting::factory()->create([
        'user_id' => $user->id,
        'title' => 'Kubernetes Infrastructure Scaling',
        'transcript' => [
            ['speaker' => 'Jordan Miller', 'start' => 0.0, 'end' => 5.0, 'text' => 'Let us review cluster autoscaling metrics.'],
        ],
    ]);
    $meeting2 = Meeting::factory()->create([
        'user_id' => $user->id,
        'title' => 'Enterprise Sales Pipeline',
        'transcript' => [
            ['speaker' => 'Elena Rostova', 'start' => 0.0, 'end' => 5.0, 'text' => 'Targeting two million in new ARR.'],
        ],
    ]);

    // Search by title match
    $response = $this->actingAs($user)->get(route('meetings.index', ['search' => 'Kubernetes']));
    $response->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Meetings/Index')
            ->has('meetings.data', 1)
            ->where('meetings.data.0.id', $meeting1->id)
        );

    // Search by transcript dialogue match
    $response2 = $this->actingAs($user)->get(route('meetings.index', ['search' => 'new ARR']));
    $response2->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Meetings/Index')
            ->has('meetings.data', 1)
            ->where('meetings.data.0.id', $meeting2->id)
        );
});

it('validates ask question input', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->post(route('meetings.ask', $meeting), [
            'question' => '',
        ]);

    $response->assertSessionHasErrors(['question']);
});

it('scopes answers to speaker list when asked who spoke', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create([
        'user_id' => $user->id,
        'transcript' => [
            ['speaker' => 'Dr. Ananth', 'start' => 23.0, 'end' => 33.0, 'text' => 'Tell me two critically endangered plants.'],
            ['speaker' => 'Jadav Payeng', 'start' => 122.0, 'end' => 125.0, 'text' => 'On my birthday I will do this.'],
        ],
    ]);

    $client = app(GroqClient::class);
    $answer = $client->answerQuestion(['transcript' => $meeting->transcript], 'Who spoke in this meeting?');
    $normalized = preg_replace('/\s+/u', ' ', $answer) ?? $answer;

    expect($normalized)->toContain('Dr. Ananth')
        ->and($normalized)->toContain('Jadav Payeng')
        ->and($normalized)->not->toContain('Executive Summary')
        ->and($normalized)->not->toContain('|');
});

it('returns not covered in this meeting when topic is absent', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create([
        'user_id' => $user->id,
        'transcript' => [
            ['speaker' => 'Alex Chen', 'start' => 10.0, 'end' => 20.0, 'text' => 'We discussed frontend architecture.'],
        ],
    ]);

    $client = app(GroqClient::class);
    $answer = $client->answerQuestion(['transcript' => $meeting->transcript], 'What is the recipe for chocolate cake?');

    expect($answer)->toBe('Not covered in this meeting.');
});
