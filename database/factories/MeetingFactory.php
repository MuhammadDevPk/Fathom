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
            'video_url' => '/videos/demo1.mp4',
            'duration_seconds' => fake()->numberBetween(120, 600),
            'transcript' => [
                [
                    'speaker' => 'Host',
                    'start' => 0.0,
                    'end' => 12.0,
                    'text' => 'Welcome to the session. Today we are discussing climate resilience and technology.',
                ],
                [
                    'speaker' => 'Dr. Ananth',
                    'start' => 12.5,
                    'end' => 24.0,
                    'text' => 'Thank you. We need to evaluate critically endangered species and biodiversity data.',
                ],
                [
                    'speaker' => 'Jadav Payeng',
                    'start' => 24.5,
                    'end' => 38.0,
                    'text' => 'Planting trees is essential, but holistic conservation must go beyond ceremonial drives.',
                ],
            ],
            'summary' => json_encode([
                'general' => "## Executive Summary\n- Discussion on climate resilience and biodiversity conservation.\n- Emphasized actionable preservation over ceremonial tree plantings.",
                'sales' => "## Strategic & Outreach Perspectives\n- Alignment of corporate sustainability mandates with grassroots frameworks.\n- Developing enterprise ESG verification metrics.",
                'engineering' => "## Systems & Infrastructure\n- Agricultural supply chain fragility under climatic disruptions.\n- Environmental telemetry and biodiversity data models.",
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'summary_template' => 'general',
            'action_items' => [
                [
                    'id' => 1,
                    'task' => 'Compile documentation on endangered flora species',
                    'assignee' => 'Dr. Ananth',
                    'completed' => false,
                ],
                [
                    'id' => 2,
                    'task' => 'Synthesize multi-tier ecological framework and action guidelines',
                    'assignee' => 'Jadav Payeng',
                    'completed' => false,
                ],
                [
                    'id' => 3,
                    'task' => 'Publish session insights and educational clips',
                    'assignee' => 'Host',
                    'completed' => true,
                ],
            ],
        ];
    }
}
