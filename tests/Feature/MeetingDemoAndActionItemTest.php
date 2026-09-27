<?php

use App\Models\Meeting;
use App\Models\User;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the public demo meeting route without auth for the shortest seeded meeting', function () {
    $this->seed(MeetingSeeder::class);

    $shortestMeeting = Meeting::query()->orderBy('duration_seconds', 'asc')->firstOrFail();

    $response = $this->get(route('demo.meeting'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Show')
            ->where('isDemo', true)
            ->where('meeting.id', $shortestMeeting->id)
            ->where('meeting.title', $shortestMeeting->title)
            ->has('transcript')
            ->has('action_items')
            ->loadDeferredProps(fn ($p) => $p
                ->has('summary')
            )
        );
});

it('persists action item checkbox toggle in session for authenticated users', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();
    $user = User::first() ?? User::factory()->create();

    // Toggle item at index 0 to true
    $response = $this->actingAs($user)
        ->from(route('meetings.show', $meeting))
        ->post(route('meetings.action-items.toggle', $meeting), [
            'index' => 0,
            'checked' => true,
        ]);

    $response->assertRedirect(route('meetings.show', $meeting));
    $response->assertSessionHas("meeting_{$meeting->id}_action_items", [
        'item_index_0' => true,
    ]);

    // Verify show view hydrates actionItemState from session
    $showResponse = $this->actingAs($user)->get(route('meetings.show', $meeting));
    $showResponse->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Show')
            ->where('actionItemState.item_index_0', true)
        );

    // Toggle item at index 0 to false (uncheck)
    $uncheckResponse = $this->actingAs($user)
        ->from(route('meetings.show', $meeting))
        ->post(route('meetings.action-items.toggle', $meeting), [
            'index' => 0,
            'checked' => false,
        ]);

    $uncheckResponse->assertRedirect(route('meetings.show', $meeting));
    $sessionData = session("meeting_{$meeting->id}_action_items");
    expect($sessionData)->not->toHaveKey('item_index_0');
});

it('redirects unauthenticated guests attempting to toggle action items to login', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $response = $this->post(route('meetings.action-items.toggle', $meeting), [
        'index' => 0,
        'checked' => true,
    ]);

    $response->assertRedirect(route('login'));
});

it('validates action item toggle input parameters', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();
    $user = User::first() ?? User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('meetings.action-items.toggle', $meeting), [
            'index' => 'not-an-int',
            'checked' => 'not-a-bool',
        ]);

    $response->assertSessionHasErrors(['index', 'checked']);
});
