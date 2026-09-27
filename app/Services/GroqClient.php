<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Config;
use RuntimeException;

class GroqClient
{
    /**
     * Create a new Groq client instance.
     */
    public function __construct(
        protected HttpFactory $http,
    ) {}

    /**
     * Generate an AI meeting summary using Groq Chat Completions API.
     *
     * @param  array<int, array<string, mixed>>  $transcript
     */
    public function generateSummary(array $transcript, string $template = 'general'): string
    {
        $apiKey = (string) Config::get('services.groq.api_key', '');
        $model = (string) Config::get('services.groq.model', 'openai/gpt-oss-120b');
        $baseUrl = (string) Config::get('services.groq.base_url', 'https://api.groq.com/openai/v1');

        if ($apiKey === '') {
            throw new RuntimeException('GROQ_API_KEY is not configured.');
        }

        $systemPrompt = $this->buildSystemPrompt($template);
        $userContent = $this->formatTranscript($transcript);

        $response = $this->http
            ->baseUrl($baseUrl)
            ->withToken($apiKey)
            ->timeout(60)
            ->post('/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => "Here is the timestamped meeting transcript:\n\n{$userContent}\n\nPlease generate the {$template} summary now.",
                    ],
                ],
                'temperature' => 0.4,
                'max_tokens' => 2048,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException("Groq API request failed with status {$response->status()}: {$response->body()}");
        }

        /** @var string|null $content */
        $content = $response->json('choices.0.message.content');

        if ($content === null || trim($content) === '') {
            throw new RuntimeException('Groq API returned an empty summary content.');
        }

        return trim($content);
    }

    /**
     * Build the system prompt tailored to the requested summary perspective.
     */
    protected function buildSystemPrompt(string $template): string
    {
        return match ($template) {
            'sales' => <<<'PROMPT'
You are a senior sales strategy copilot at Fathom.
Synthesize the provided meeting transcript strictly from a Commercial & Revenue perspective.
Structure your output in clean Markdown with these exact sections:
## Deal Overview & Prospect Sentiment
- Summarize buyer intent, key stakeholders present, urgency, and general sentiment.
## Customer Pain Points & Objections
- List specific organizational friction, legacy limitations, or technical objections raised.
## Commercial & Pricing Discussion
- Detail timeline expectations, budget constraints, contract scale, and procurement hurdles.
## Next Steps & Commitments
- Outline specific follow-ups, deliverables promised, and meeting deadlines with owner attributions.
Keep the style crisp, data-driven, and high signal.
PROMPT,

            'engineering' => <<<'PROMPT'
You are a principal systems architect copilot at Fathom.
Synthesize the provided meeting transcript strictly from a Technical & Systems Engineering perspective.
Structure your output in clean Markdown with these exact sections:
## Technical Architecture & Systems Impact
- Core architectural decisions, system boundaries, and API/data flow specifications.
## Key Technical Decisions & Tradeoffs
- Architectural choices made, alternatives rejected, performance/latency implications.
## Implementation Blockers & Risks
- Security considerations, migration risks, edge cases, and testing dependencies.
## Engineering Action Items
- Concrete implementation tasks, assigned engineers, and immediate technical milestones.
Keep the style concise, exact, and actionable for developers.
PROMPT,

            default => <<<'PROMPT'
You are an executive meeting intelligence assistant at Fathom.
Synthesize the provided meeting transcript into a comprehensive, high-value executive summary.
Structure your output in clean Markdown with these exact sections:
## Executive Summary
- Concise 2-3 sentence strategic synopsis of the conversation and overall outcomes.
## Key Decisions
- Bullet points detailing major decisions agreed upon by participants.
## Discussion Highlights
- Core themes, critical feedback, and pivotal discussion points.
## Action Items
- Bullet points detailing concrete next steps, assigned owners (@Name), and target timelines.
Keep the style polished, professional, and easy to scan.
PROMPT,
        };
    }

    /**
     * Format transcript cues into readable dialogue text.
     *
     * @param  array<int, array<string, mixed>>  $transcript
     */
    protected function formatTranscript(array $transcript): string
    {
        $lines = [];

        foreach ($transcript as $cue) {
            $speaker = (string) ($cue['speaker'] ?? 'Unknown');
            $text = (string) ($cue['text'] ?? '');
            $startSeconds = (float) ($cue['start'] ?? 0);

            $minutes = (int) floor($startSeconds / 60);
            $seconds = (int) ($startSeconds % 60);
            $timestamp = sprintf('%02d:%02d', $minutes, $seconds);

            $lines[] = "[{$timestamp}] {$speaker}: {$text}";
        }

        return implode("\n", $lines);
    }
}
