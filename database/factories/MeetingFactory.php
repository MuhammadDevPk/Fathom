<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'duration_seconds' => fake()->numberBetween(180, 1800),
            'transcript' => [
                [
                    'speaker' => 'Alex Chen',
                    'start' => 0.0,
                    'end' => 4.5,
                    'text' => 'Thanks everyone for jumping on. Let us review the Q3 product roadmap and sprint goals.',
                ],
                [
                    'speaker' => 'Sarah Connor',
                    'start' => 5.2,
                    'end' => 9.8,
                    'text' => 'Sounds great. I have updated the design tokens and the initial wireframes for the workspace.',
                ],
                [
                    'speaker' => 'David Miller',
                    'start' => 10.3,
                    'end' => 15.1,
                    'text' => 'The backend query latency optimizations are ready to deploy alongside the new schema changes.',
                ],
            ],
            'summary' => "## Executive Summary\nThe team aligned on the Q3 product roadmap with priority on UX performance and design tokens.\n\n### Key Discussion Points\n- **Design System:** Sarah updated the design tokens.\n- **Engineering:** David reported backend query latency optimizations ready for release.",
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Review updated design tokens in Figma',
                    'assignee' => 'Sarah Connor',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Deploy backend latency optimizations to staging',
                    'assignee' => 'David Miller',
                    'completed' => true,
                ],
            ],
        ];
    }
}
