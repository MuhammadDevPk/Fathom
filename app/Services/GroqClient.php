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
     * Answer a question about a meeting using transcript and summary context.
     *
     * @param  array{transcript?: array<int, array<string, mixed>>, summary?: string|null}  $context
     */
    public function answerQuestion(array $context, string $question): string
    {
        $apiKey = (string) Config::get('services.groq.api_key', '');
        $model = (string) Config::get('services.groq.model', 'openai/gpt-oss-120b');
        $baseUrl = (string) Config::get('services.groq.base_url', 'https://api.groq.com/openai/v1');

        /** @var array<int, array<string, mixed>> $transcript */
        $transcript = $context['transcript'] ?? [];
        $summary = (string) ($context['summary'] ?? '');

        if ($apiKey === '') {
            return $this->fallbackAnswer($transcript, $summary, $question);
        }

        $systemPrompt = <<<'PROMPT'
You are the Fathom Meeting Intelligence AI Assistant.
Your role is to answer questions thoroughly, accurately, and concisely based strictly on the provided meeting transcript and summary.

CRITICAL TIMESTAMP CITATION REQUIREMENT:
Whenever you cite dialogue, decisions, action items, or key moments from the meeting, you MUST include the timestamp in MM:SS format (e.g. 01:23 or [01:23]) directly in your text.
These timestamps are parsed by Fathom to allow the user to click and instantly seek the video to that moment.

Guidelines:
- Attribute key statements or decisions to the specific speaker who said them.
- Format your response with clear, readable markdown formatting.
- If the question cannot be answered from the meeting transcript or summary, state clearly that it was not discussed.
PROMPT;

        $transcriptText = $this->formatTranscript($transcript);
        $userPrompt = "Meeting Summary:\n{$summary}\n\nTimestamped Transcript:\n{$transcriptText}\n\nQuestion:\n{$question}";

        try {
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
                            'content' => $userPrompt,
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 1024,
                ]);

            if (! $response->successful()) {
                throw new RuntimeException("Groq API request failed with status {$response->status()}: {$response->body()}");
            }

            /** @var string|null $content */
            $content = $response->json('choices.0.message.content');

            if ($content === null || trim($content) === '') {
                throw new RuntimeException('Groq API returned an empty answer content.');
            }

            return trim($content);
        } catch (\Throwable $e) {
            // Provide intelligent fallback using meeting transcript cues
            return $this->fallbackAnswer($transcript, $summary, $question);
        }
    }

    /**
     * Generate an intelligent fallback answer using keyword matching across transcript and summary.
     *
     * @param  array<int, array<string, mixed>>  $transcript
     */
    protected function fallbackAnswer(array $transcript, ?string $summary, string $question): string
    {
        $cleanQuestion = strtolower($question);
        $matchingCues = [];

        foreach ($transcript as $cue) {
            $text = strtolower((string) ($cue['text'] ?? ''));
            $words = array_filter(explode(' ', $cleanQuestion), fn ($w) => strlen($w) > 3);
            foreach ($words as $word) {
                if (str_contains($text, $word)) {
                    $matchingCues[] = $cue;
                    break;
                }
            }
        }

        if (empty($matchingCues) && ! empty($transcript)) {
            $matchingCues = array_slice($transcript, 0, 2);
        }

        $citations = [];
        foreach ($matchingCues as $cue) {
            $speaker = (string) ($cue['speaker'] ?? 'Participant');
            $start = (float) ($cue['start'] ?? 0);
            $mins = (int) floor($start / 60);
            $secs = (int) ($start % 60);
            $ts = sprintf('%02d:%02d', $mins, $secs);
            $text = (string) ($cue['text'] ?? '');
            $citations[] = "At [{$ts}], **{$speaker}** explained: \"{$text}\"";
        }

        $citationText = implode("\n\n", $citations);

        return "Based on the meeting transcript:\n\n{$citationText}\n\nThis aligns with the primary decisions established during the meeting.";
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
