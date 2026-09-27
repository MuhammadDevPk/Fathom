<?php

use App\Models\Meeting;
use App\Models\User;
use Database\Seeders\MeetingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

it('seeds the demo user and associates meetings', function () {
    $this->seed(MeetingSeeder::class);

    $demoUser = User::where('email', 'demo@fathom.test')->first();
    expect($demoUser)->not->toBeNull();
    expect(Meeting::where('user_id', $demoUser->id)->count())->toBe(5);
});

it('logs into demo user and redirects to meetings index via POST /demo/login', function () {
    $this->seed(MeetingSeeder::class);

    $response = $this->post(route('demo.login'));

    $response->assertRedirect(route('meetings.index'));
    $this->assertAuthenticated();
    expect(auth()->user()?->email)->toBe('demo@fathom.test');
});

it('rate limits POST /demo/login to 10 requests per minute', function () {
    $this->seed(MeetingSeeder::class);

    for ($i = 0; $i < 10; $i++) {
        $response = $this->post(route('demo.login'));
        $response->assertRedirect(route('meetings.index'));
        auth()->logout();
    }

    $eleventh = $this->post(route('demo.login'));
    $eleventh->assertStatus(429);
});

it('allows unauthenticated guests to view meeting via valid signed share URL in read-only mode', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $signedUrl = URL::signedRoute('meetings.share', ['meeting' => $meeting->id]);

    $response = $this->get($signedUrl);

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Show')
            ->where('isDemo', true)
            ->where('meeting.id', $meeting->id)
            ->has('share_url')
        );
});

it('share route returns video_url and summary props', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $signedUrl = URL::signedRoute('meetings.share', ['meeting' => $meeting->id]);

    $response = $this->get($signedUrl);

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Show')
            ->where('isDemo', true)
            ->where('meeting.id', $meeting->id)
            ->whereNot('meeting.video_url', null)
            ->loadDeferredProps(fn ($p) => $p
                ->has('summary')
                ->whereNot('summary', null)
            )
        );
});

it('returns 403 when share URL has an invalid or tampered signature', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();

    $signedUrl = URL::signedRoute('meetings.share', ['meeting' => $meeting->id]);
    $tamperedUrl = $signedUrl.'tampered';

    $response = $this->get($tamperedUrl);
    $response->assertForbidden();

    $unsignedResponse = $this->get(route('meetings.share', ['meeting' => $meeting->id]));
    $unsignedResponse->assertForbidden();
});

it('prevents demo user from mutating data with 403 responses', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();
    $demoUser = User::where('email', 'demo@fathom.test')->firstOrFail();

    // 1. Highlight store
    $highlightResponse = $this->actingAs($demoUser)
        ->post(route('meetings.highlights.store', $meeting), [
            'timestamp_seconds' => 12,
            'label' => 'Key Discussion',
            'note' => 'Demo highlight attempt',
        ]);
    $highlightResponse->assertForbidden();

    // 2. Summary regenerate
    $summaryResponse = $this->actingAs($demoUser)
        ->post(route('meetings.summary.generate', $meeting), [
            'template' => 'engineering',
        ]);
    $summaryResponse->assertForbidden();

    // 3. Ask AI
    $askResponse = $this->actingAs($demoUser)
        ->post(route('meetings.ask', $meeting), [
            'question' => 'What was discussed?',
        ]);
    $askResponse->assertForbidden();

    // 4. Action item toggle
    $actionItemResponse = $this->actingAs($demoUser)
        ->post(route('meetings.action-items.toggle', $meeting), [
            'index' => 0,
            'checked' => true,
        ]);
    $actionItemResponse->assertForbidden();
});

it('provides signed share_url to authenticated users in meeting detail view', function () {
    $this->seed(MeetingSeeder::class);
    $meeting = Meeting::firstOrFail();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('meetings.show', $meeting));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Meetings/Show')
            ->where('isDemo', false)
            ->where('meeting.id', $meeting->id)
            ->has('share_url')
        );
});
