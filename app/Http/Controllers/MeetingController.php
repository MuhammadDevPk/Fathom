<?php

namespace App\Http\Controllers;

use App\Http\Requests\AskMeetingQuestionRequest;
use App\Jobs\AnswerMeetingQuestion;
use App\Jobs\GenerateMeetingSummary;
use App\Models\Meeting;
use App\Models\User;
use App\Services\GroqClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
     * Log in directly as the demo user and redirect to the meetings dashboard.
     */
    public function demoLogin(Request $request): RedirectResponse
    {
        $demoUser = User::firstWhere('email', 'demo@fathom.test') ?? User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@fathom.test',
        ]);

        auth()->login($demoUser);
        $request->session()->regenerate();

        return redirect()->route('meetings.index');
    }

    /**
     * Display the public demo meeting view.
     */
    public function demo(Request $request): Response
    {
        $meeting = Meeting::query()
            ->orderBy('duration_seconds', 'asc')
            ->firstOrFail();

        return $this->renderMeetingDetail($request, $meeting, isDemo: true);
    }

    /**
     * Display a shared meeting via validated signed URL.
     */
    public function share(Request $request, Meeting $meeting): Response
    {
        $sessionKey = "share_verified_{$meeting->id}";
        $hasValidSig = $request->hasValidSignature() || $request->hasValidSignature(false);

        if ($hasValidSig) {
            $request->session()->put($sessionKey, true);
        } elseif (! $request->session()->get($sessionKey)) {
            abort(403, 'Invalid or tampered share link.');
        }

        return $this->renderMeetingDetail($request, $meeting, isDemo: true);
    }

    /**
     * Display the specified meeting detail view.
     */
    public function show(Request $request, Meeting $meeting): Response
    {
        return $this->renderMeetingDetail($request, $meeting, isDemo: false);
    }

    /**
     * Build props and render the meeting detail view.
     */
    private function renderMeetingDetail(Request $request, Meeting $meeting, bool $isDemo = false): Response
    {
        $template = (string) $request->input('template', $meeting->summary_template ?: 'general');
        if (! in_array($template, ['general', 'sales', 'engineering'], true)) {
            $template = 'general';
        }

        $sessionKey = "meeting_{$meeting->id}_qa";
        /** @var array<int, array{id: string, question: string, answer: string, created_at: string}> $qaHistory */
        $qaHistory = (array) $request->session()->get($sessionKey, []);

        $isDemoUser = $request->user()?->email === 'demo@fathom.test';
        $isReadOnly = $isDemo || $isDemoUser;

        $actionItemSessionKey = "meeting_{$meeting->id}_action_items";
        /** @var array<string, bool> $actionItemState */
        $actionItemState = [];

        if (! $isReadOnly) {
            if (! $request->session()->has($actionItemSessionKey)) {
                $initialState = [];
                foreach ($meeting->action_items ?? [] as $idx => $item) {
                    if (! empty($item['completed'])) {
                        $initialState["item_index_{$idx}"] = true;
                    }
                }
                $request->session()->put($actionItemSessionKey, $initialState);
            }
            $actionItemState = (array) $request->session()->get($actionItemSessionKey, []);
        }

        $shareUrl = URL::signedRoute('meetings.share', ['meeting' => $meeting->id]);

        $meetingData = [
            'id' => $meeting->id,
            'title' => $meeting->title,
            'video_url' => $meeting->video_url ?: '/videos/demo1.mp4',
            'duration_seconds' => $meeting->duration_seconds,
            'created_at' => $meeting->created_at?->toISOString() ?? (string) $meeting->created_at,
        ];

        return Inertia::render('Meetings/Show', [
            'meeting' => $meetingData,
            'transcript' => $meeting->transcript ?? [],
            'active_template' => $template,
            'summary' => Inertia::defer(fn () => $meeting->getSummaryForTemplate($template)),
            'highlights' => $meeting->highlights()->orderBy('timestamp_seconds')->get(['id', 'meeting_id', 'timestamp_seconds', 'label', 'note']),
            'action_items' => $meeting->action_items ?? [],
            'qa_history' => $qaHistory,
            'action_item_state' => (object) $actionItemState,
            'actionItemState' => (object) $actionItemState,
            'share_url' => $shareUrl,
            'isDemo' => $isReadOnly,
        ]);
    }

    /**
     * Toggle the completion state of an action item in the session.
     */
    public function toggleActionItem(Request $request, Meeting $meeting): RedirectResponse
    {
        if ($request->user()?->email === 'demo@fathom.test') {
            abort(403, 'Demo account is read-only. Sign up for full access.');
        }

        $validated = $request->validate([
            'index' => ['required', 'integer', 'min:0'],
            'checked' => ['required', 'boolean'],
        ]);

        $index = (int) $validated['index'];
        $checked = (bool) $validated['checked'];

        $sessionKey = "meeting_{$meeting->id}_action_items";
        /** @var array<string, bool> $state */
        $state = (array) $request->session()->get($sessionKey, []);

        $itemKey = "item_index_{$index}";
        if ($checked) {
            $state[$itemKey] = true;
        } else {
            unset($state[$itemKey]);
        }

        $request->session()->put($sessionKey, $state);

        return back();
    }

    /**
     * Dispatch an asynchronous queued job to generate an AI summary for the given template.
     */
    public function generateSummary(Request $request, Meeting $meeting): RedirectResponse
    {
        if ($request->user()?->email === 'demo@fathom.test') {
            abort(403, 'Demo account is read-only. Sign up for full access.');
        }

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
        if ($request->user()?->email === 'demo@fathom.test') {
            abort(403, 'Demo account is read-only. Sign up for full access.');
        }

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
