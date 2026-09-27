<?php

namespace App\Http\Controllers;

use App\Http\Requests\AskMeetingQuestionRequest;
use App\Jobs\AnswerMeetingQuestion;
use App\Jobs\GenerateMeetingSummary;
use App\Models\Meeting;
use App\Services\GroqClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MeetingController extends Controller
{
    /**
     * Display a paginated listing of meetings.
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $searchTerm = is_string($search) && trim($search) !== '' ? trim($search) : null;

        $meetings = Meeting::query()
            ->search($searchTerm)
            ->orderByDesc('created_at')
            ->select(['id', 'title', 'duration_seconds', 'created_at', 'transcript'])
            ->paginate(12)
            ->withQueryString()
            ->through(function (Meeting $meeting): array {
                /** @var list<string> $speakers */
                $speakers = collect($meeting->transcript ?? [])
                    ->pluck('speaker')
                    ->filter(fn ($s): bool => is_string($s) && $s !== '')
                    ->unique()
                    ->values()
                    ->all();

                return [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'duration_seconds' => $meeting->duration_seconds,
                    'created_at' => $meeting->created_at?->toIso8601String() ?? '',
                    'speakers' => $speakers,
                    'speaker_count' => count($speakers),
                ];
            });

        return Inertia::render('Meetings/Index', [
            'meetings' => $meetings,
            'filters' => [
                'search' => $searchTerm ?? '',
            ],
        ]);
    }

    /**
     * Display the specified meeting detail view.
     */
    public function show(Request $request, Meeting $meeting): Response
    {
        $template = (string) $request->input('template', $meeting->summary_template ?: 'general');
        if (! in_array($template, ['general', 'sales', 'engineering'], true)) {
            $template = 'general';
        }

        $sessionKey = "meeting_{$meeting->id}_qa";
        /** @var array<int, array{id: string, question: string, answer: string, created_at: string}> $qaHistory */
        $qaHistory = (array) $request->session()->get($sessionKey, []);

        return Inertia::render('Meetings/Show', [
            'meeting' => $meeting->only(['id', 'title', 'video_url', 'duration_seconds', 'created_at']),
            'transcript' => $meeting->transcript ?? [],
            'active_template' => $template,
            'summary' => Inertia::defer(fn () => $meeting->getSummaryForTemplate($template)),
            'highlights' => $meeting->highlights()->orderBy('timestamp_seconds')->get(['id', 'meeting_id', 'timestamp_seconds', 'label', 'note']),
            'action_items' => $meeting->action_items ?? [],
            'qa_history' => $qaHistory,
        ]);
    }

    /**
     * Dispatch an asynchronous queued job to generate an AI summary for the given template.
     */
    public function generateSummary(Request $request, Meeting $meeting): RedirectResponse
    {
        $template = (string) $request->input('template', 'general');
        if (! in_array($template, ['general', 'sales', 'engineering'], true)) {
            $template = 'general';
        }

        GenerateMeetingSummary::dispatch($meeting, $template);

        return back()->with('success', 'AI summary generation has been queued.');
    }

    /**
     * Ask a question about the meeting answered by AI.
     */
    public function ask(AskMeetingQuestionRequest $request, Meeting $meeting, GroqClient $groq): RedirectResponse
    {
        $question = (string) $request->validated('question');

        try {
            $job = new AnswerMeetingQuestion($meeting, $question);
            $job->handle($groq);

            return back()->with('success', 'Question answered.');
        } catch (Throwable $e) {
            return back()->withErrors([
                'question' => 'Unable to answer question at this time. Please try again.',
            ]);
        }
    }
}
