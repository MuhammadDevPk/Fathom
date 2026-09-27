<?php

use App\Models\Highlight;
use App\Models\Meeting;
use App\Models\User;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes a meeting', function () {
    $user = User::factory()->create();
    $meeting = Meeting::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('meetings.destroy', $meeting));

    expect(Meeting::find($meeting->id))->toBeNull();
    $response->assertRedirect(route('meetings.index'));
});

it('forbids deleting another user\'s meeting', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $meeting = Meeting::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($otherUser)->delete(route('meetings.destroy', $meeting));

    $response->assertForbidden();
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
