<?php

namespace Database\Factories;

use App\Models\Highlight;
use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Highlight>
 */
class HighlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meeting_id' => Meeting::factory(),
            'timestamp_seconds' => fake()->numberBetween(5, 300),
            'label' => fake()->randomElement(['Decision', 'Action Item', 'Key Question', 'Feedback']),
            'note' => fake()->sentence(),
        ];
    }
}
