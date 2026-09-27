<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHighlightRequest;
use App\Models\Meeting;
use Illuminate\Http\RedirectResponse;

class HighlightController extends Controller
{
    /**
     * Store a newly created highlight in storage.
     */
    public function store(StoreHighlightRequest $request, Meeting $meeting): RedirectResponse
    {
        if ($request->user()?->email === 'demo@fathom.test') {
            abort(403, 'Demo account is read-only. Sign up for full access.');
        }

        $validated = $request->validated();
        $validated['label'] = ! empty($validated['label']) ? (string) $validated['label'] : 'Key Moment';

        $meeting->highlights()->create($validated);

        return back()->with('success', 'Highlight saved successfully.');
    }
}
