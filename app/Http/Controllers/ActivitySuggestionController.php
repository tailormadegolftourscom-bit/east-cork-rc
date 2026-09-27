<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivitySuggestion;
use Illuminate\Http\Request;

/**
 * Suggestions page for an activity that is still being planned.
 *
 * Anyone can read the approved ideas; only a registered parent can add one,
 * and it waits for an admin before it appears. Names are never shown.
 */
class ActivitySuggestionController extends Controller
{
    public function show(Request $request, Activity $activity)
    {
        abort_unless($activity->is_active && $activity->suggestions_open, 404);

        $user = $request->user();
        $isParent = $user && $user->user_type === 'parent';

        return view('activities.suggestions', [
            'activity' => $activity->load('venue'),
            'approved' => $activity->suggestions()->approved()->orderBy('approved_at')->orderBy('id')->get(),
            // A parent sees their own ideas still waiting, so they know it
            // arrived rather than wondering whether to send it again.
            'mine' => $isParent
                ? $activity->suggestions()->pending()->where('parent_id', $user->id)->latest()->get()
                : collect(),
            'canSuggest' => $isParent,
        ]);
    }

    public function store(Request $request, Activity $activity)
    {
        abort_unless($activity->is_active && $activity->suggestions_open, 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ], [
            'body.required' => 'Please type your suggestion.',
        ]);

        ActivitySuggestion::create([
            'activity_id' => $activity->id,
            'parent_id' => $request->user()->id,
            'body' => trim($validated['body']),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('activities.suggestions', $activity)
            ->with('success', 'Thank you — your suggestion will appear here once it has been approved.');
    }
}
