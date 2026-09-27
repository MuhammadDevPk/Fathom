<?php

use App\Models\Meeting;
use App\Models\User;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

it('creates a highlight for a meeting', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create([
        'user_id' => $user->id,
        'duration_seconds' => 120,
    ]);

    $payload = [
        'timestamp_seconds' => 45,
        'label' => 'Architecture Decision',
        'note' => 'Standardized on Reka UI primitives for accessible modals and tabs.',
    ];

    $response = $this->actingAs($user)
        ->post(route('meetings.highlights.store', $meeting), $payload);

    $response->assertRedirect();
    $this->assertDatabaseHas('highlights', [
        'meeting_id' => $meeting->id,
        'timestamp_seconds' => 45,
        'label' => 'Architecture Decision',
        'note' => 'Standardized on Reka UI primitives for accessible modals and tabs.',
    ]);
});

it('lists highlights on the meeting detail page', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();
    $user = User::first() ?? User::factory()->create();

    $response = $this->actingAs($user)->get(route('meetings.show', $meeting));

    $response->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Meetings/Show')
            ->has('highlights')
            ->has('action_items')
            ->has('highlights.0', fn (AssertableInertia $highlight) => $highlight
                ->has('id')
                ->where('meeting_id', $meeting->id)
                ->has('timestamp_seconds')
                ->has('label')
                ->has('note')
            )
        );
});

it('validates highlight required fields and timestamp boundaries', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create([
        'user_id' => $user->id,
        'duration_seconds' => 100,
    ]);

    // Note is required
    $responseNoNote = $this->actingAs($user)
        ->post(route('meetings.highlights.store', $meeting), [
            'timestamp_seconds' => 10,
            'label' => 'Note Missing',
            'note' => '',
        ]);
    $responseNoNote->assertSessionHasErrors(['note']);

    // Timestamp exceeding meeting duration
    $responseOutOfBounds = $this->actingAs($user)
        ->post(route('meetings.highlights.store', $meeting), [
            'timestamp_seconds' => 200,
            'label' => 'Out of Bounds',
            'note' => 'This should fail validation',
        ]);
    $responseOutOfBounds->assertSessionHasErrors(['timestamp_seconds']);
});
