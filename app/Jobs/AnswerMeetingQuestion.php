<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Services\GroqClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AnswerMeetingQuestion implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Meeting $meeting,
        public string $question,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GroqClient $groq): string
    {
        try {
            $context = [
                'transcript' => $this->meeting->transcript ?? [],
                'summary' => $this->meeting->getSummaryForTemplate('general') ?? (string) $this->meeting->summary,
            ];

            $answer = $groq->answerQuestion($context, $this->question);

            if (session()->isStarted()) {
                $sessionKey = "meeting_{$this->meeting->id}_qa";
                /** @var array<int, array{id: string, question: string, answer: string, created_at: string}> $history */
                $history = (array) session()->get($sessionKey, []);
                $history[] = [
                    'id' => (string) Str::uuid(),
                    'question' => $this->question,
                    'answer' => $answer,
                    'created_at' => now()->toIso8601String(),
                ];
                session()->put($sessionKey, $history);
            }

            return $answer;
        } catch (Throwable $e) {
            Log::warning("AnswerMeetingQuestion failed for meeting {$this->meeting->id}: {$e->getMessage()}");
            throw $e;
        }
    }
}
