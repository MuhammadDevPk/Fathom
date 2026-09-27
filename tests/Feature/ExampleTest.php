<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('returns a successful response for public landing page', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
        );
});

test('authenticated user can view landing page with dashboard access', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
        );
});
