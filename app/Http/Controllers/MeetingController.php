<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
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
    public function show(Meeting $meeting): Response
    {
        return Inertia::render('Meetings/Show', [
            'meeting' => $meeting->only(['id', 'title', 'video_url', 'duration_seconds', 'created_at']),
            'transcript' => $meeting->transcript ?? [],
            'summary' => $meeting->summary,
        ]);
    }
}
