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
You are answering a single question about a meeting transcript.

Rules:
- Answer ONLY the question asked. Do not summarize the whole meeting.
- Do not list speakers unless the user explicitly asks who spoke.
- Do not include an agenda, overview, or unrelated context.
- Match the depth of the question: short question → short answer (2–4 sentences for simple questions).
- Use plain prose for simple answers. Use bullets only if the user asks for a list.
- No markdown tables. Ever. Unless the user explicitly requests a table.
- Cite specific moments as [MM:SS] inline. One or two citations per answer.
- If the answer is not in the transcript, say "Not covered in this meeting." in one sentence.
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

        // Check if user specifically asked who spoke
        if (str_contains($cleanQuestion, 'who spoke') || str_contains($cleanQuestion, 'speakers') || str_contains($cleanQuestion, 'participants')) {
            $speakers = collect($transcript)->pluck('speaker')->filter(fn ($s) => is_string($s) && $s !== '')->unique()->values()->all();
            if (! empty($speakers)) {
                return 'The following participants spoke in this meeting: '.implode(', ', $speakers).'.';
            }
        }

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

        if (empty($matchingCues)) {
            return 'Not covered in this meeting.';
        }

        $matchingCues = array_slice($matchingCues, 0, 2);

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

        return implode(' ', $citations);
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

Rules:
- Structure your output in clean Markdown with these exact sections.
- Under each section heading, provide a concise one-line headline, followed by 3 to 5 structured bullet points.
- Do NOT write free-form narrative paragraphs.

## Deal Overview & Prospect Sentiment
- 3 to 5 bullets summarizing buyer intent, key stakeholders present, urgency, and general sentiment.
## Customer Pain Points & Objections
- 3 to 5 bullets listing specific organizational friction, legacy limitations, or technical objections raised.
## Commercial & Pricing Discussion
- 3 to 5 bullets detailing timeline expectations, budget constraints, contract scale, and procurement hurdles.
## Next Steps & Commitments
- 3 to 5 bullets outlining specific follow-ups, deliverables promised, and meeting deadlines with owner attributions.
PROMPT,

            'engineering' => <<<'PROMPT'
You are a principal systems architect copilot at Fathom.
Synthesize the provided meeting transcript strictly from a Technical & Systems Engineering perspective.

Rules:
- Structure your output in clean Markdown with these exact sections.
- Under each section heading, provide a concise one-line headline, followed by 3 to 5 structured bullet points.
- Do NOT write free-form narrative paragraphs.

## Technical Architecture & Systems Impact
- 3 to 5 bullets detailing core architectural decisions, system boundaries, and API/data flow specifications.
## Key Technical Decisions & Tradeoffs
- 3 to 5 bullets detailing architectural choices made, alternatives rejected, and performance/latency implications.
## Implementation Blockers & Risks
- 3 to 5 bullets detailing security considerations, migration risks, edge cases, and testing dependencies.
## Engineering Action Items
- 3 to 5 bullets detailing concrete implementation tasks, assigned engineers, and immediate technical milestones.
PROMPT,

            default => <<<'PROMPT'
You are an executive meeting intelligence assistant at Fathom.
Synthesize the provided meeting transcript into a comprehensive, high-value executive summary.

Rules:
- Structure your output in clean Markdown with these exact sections.
- Under each section heading, provide a concise one-line headline, followed by 3 to 5 structured bullet points.
- Do NOT write free-form narrative paragraphs.

## Executive Summary
- 3 to 5 bullets providing a strategic synopsis of the conversation and overall outcomes.
## Key Decisions
- 3 to 5 bullets detailing major decisions agreed upon by participants.
## Discussion Highlights
- 3 to 5 bullets highlighting core themes, critical feedback, and pivotal discussion points.
## Action Items
- 3 to 5 bullets detailing concrete next steps, assigned owners (@Name), and target timelines.
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
