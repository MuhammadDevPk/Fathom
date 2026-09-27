<?php

use App\Models\Highlight;
use App\Models\Meeting;
use App\Models\User;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows any registered user to delete a meeting', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $meeting = Meeting::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($otherUser)->delete(route('meetings.destroy', $meeting));

    expect(Meeting::find($meeting->id))->toBeNull();
    $response->assertRedirect(route('meetings.index'));
});

it('redirects unauthenticated guests when attempting to delete a meeting', function () {
    $meeting = Meeting::factory()->create();

    $response = $this->delete(route('meetings.destroy', $meeting));

    $response->assertRedirect(route('login'));
    expect(Meeting::find($meeting->id))->not->toBeNull();
});

it('seeder is idempotent', function () {
    $this->seed(MeetingSeeder::class);
    $initialMeetingCount = Meeting::count();
    $initialHighlightCount = Highlight::count();

    expect($initialMeetingCount)->toBeGreaterThan(0);

    // Run seeder a second time
    $this->seed(MeetingSeeder::class);

    expect(Meeting::count())->toBe($initialMeetingCount)
        ->and(Highlight::count())->toBe($initialHighlightCount);
});
