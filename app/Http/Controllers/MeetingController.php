<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateMeetingSummary;
use App\Models\Meeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MeetingController extends Controller
{
    /**
     * Display a paginated listing of meetings.
     */
    public function index(Request $request): Response
    {
        $meetings = Meeting::query()
            ->orderByDesc('created_at')
            ->select(['id', 'title', 'duration_seconds', 'created_at', 'transcript'])
            ->paginate(12)
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

        return Inertia::render('Meetings/Show', [
            'meeting' => $meeting->only(['id', 'title', 'video_url', 'duration_seconds', 'created_at']),
            'transcript' => $meeting->transcript ?? [],
            'active_template' => $template,
            'summary' => Inertia::defer(fn () => $meeting->getSummaryForTemplate($template)),
            'highlights' => $meeting->highlights()->orderBy('timestamp_seconds')->get(['id', 'meeting_id', 'timestamp_seconds', 'label', 'note']),
            'action_items' => $meeting->action_items ?? [],
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
}
