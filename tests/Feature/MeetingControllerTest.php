<?php

use App\Models\Meeting;
use App\Models\User;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the meetings index page with 5 seeded meeting cards', function () {
    $this->seed(MeetingSeeder::class);

    $response = $this->get(route('meetings.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Index')
            ->has('meetings.data', 5)
            ->where('meetings.total', 5)
            ->has('meetings.data.0', fn ($meeting) => $meeting
                ->has('id')
                ->has('title')
                ->has('duration_seconds')
                ->has('created_at')
                ->has('speakers')
                ->has('speaker_count')
            )
        );
});

it('renders meeting detail view with video, transcript, and summary', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $response = $this->get(route('meetings.show', $meeting));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Show')
            ->has('meeting', fn ($m) => $m
                ->where('id', $meeting->id)
                ->where('title', $meeting->title)
                ->where('video_url', $meeting->video_url)
                ->where('duration_seconds', $meeting->duration_seconds)
                ->etc()
            )
            ->has('transcript')
            ->where('summary', $meeting->summary)
        );
});

it('allows authenticated users to view dashboard which renders meetings', function () {
    $user = User::factory()->create();
    $this->seed(MeetingSeeder::class);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Index')
            ->has('meetings.data', 5)
        );
});
