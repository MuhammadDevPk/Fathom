<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Services\GroqClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateMeetingSummary implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Meeting $meeting,
        public string $template = 'general',
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GroqClient $groq): void
    {
        try {
            $transcript = $this->meeting->transcript ?? [];
            if (empty($transcript)) {
                return;
            }

            $summaryText = $groq->generateSummary($transcript, $this->template);

            /** @var array<string, string> $summaries */
            $summaries = [];
            $rawSummary = $this->meeting->summary;

            if ($rawSummary !== null && trim($rawSummary) !== '') {
                $decoded = json_decode($rawSummary, true);
                if (is_array($decoded)) {
                    /** @var array<string, string> $summaries */
                    $summaries = $decoded;
                } else {
                    $summaries['general'] = $rawSummary;
                }
            }

            $summaries[$this->template] = $summaryText;

            $this->meeting->update([
                'summary' => json_encode($summaries, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'summary_template' => $this->template,
            ]);
        } catch (Throwable $e) {
            Log::warning("GenerateMeetingSummary failed for meeting {$this->meeting->id} [template: {$this->template}]: {$e->getMessage()}");
            // Graceful fallback: existing seeded summary remains intact in database
        }
    }
}
