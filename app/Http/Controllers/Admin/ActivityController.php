<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivitySuggestion;
use App\Models\AdminAuditLog;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Activities and the venues they use, on one admin screen: the two are edited
 * together, and a separate page for four venues would be a click for nothing.
 */
class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with('venue')->withCount(['signups', 'volunteers'])
            ->orderByRaw('CASE WHEN starts_on IS NULL THEN 0 ELSE 1 END')
            ->orderByDesc('starts_on')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('admin.activities.index', [
            'activities' => $activities,
            'venues' => Venue::withCount('activities')->ordered()->get(),
            'suggestions' => ActivitySuggestion::with(['activity', 'parent'])
                ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'approved' THEN 1 ELSE 2 END")
                ->latest()
                ->get(),
        ]);
    }

    /** Who is coming and who is helping, with the contacts to reach them. */
    public function people(Activity $activity)
    {
        return view('admin.activities.people', [
            'activity' => $activity,
            'signups' => $activity->signups()
                ->with(['child.owner', 'child.schoolLink.currentSchool', 'child.schoolLink.currentSchoolClass', 'parent'])
                ->oldest()
                ->get(),
            'volunteers' => $activity->volunteers()->oldest()->get(),
        ]);
    }

    /** Approve, hide, or return a suggestion to pending. */
    public function moderateSuggestion(Request $request, ActivitySuggestion $suggestion)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'hidden'])],
        ]);

        $suggestion->update([
            'status' => $validated['status'],
            'approved_at' => $validated['status'] === 'approved' ? ($suggestion->approved_at ?? now()) : null,
        ]);

        AdminAuditLog::record('activity_suggestion.'.$validated['status'], $suggestion, null, [
            'activity' => $suggestion->activity?->title,
            'body' => $suggestion->body,
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Suggestion '.$validated['status'].'.');
    }

    public function create()
    {
        return view('admin.activities.form', [
            'activity' => new Activity(['status' => 'planned', 'is_active' => true]),
            'venues' => Venue::ordered()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $activity = Activity::create($this->validateActivity($request));

        AdminAuditLog::record('activity.create', $activity, null, ['title' => $activity->title]);

        return redirect()->route('admin.activities.index')->with('success', $activity->title.' added.');
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.form', [
            'activity' => $activity,
            'venues' => Venue::ordered()->get(),
        ]);
    }

    public function update(Request $request, Activity $activity)
    {
        $activity->update($this->validateActivity($request));

        AdminAuditLog::record('activity.update', $activity, null, ['title' => $activity->title]);

        return redirect()->route('admin.activities.index')->with('success', $activity->title.' updated.');
    }

    public function destroy(Activity $activity)
    {
        AdminAuditLog::record('activity.delete', $activity, null, ['title' => $activity->title]);

        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', $activity->title.' deleted.');
    }

    public function createVenue()
    {
        return view('admin.activities.venue-form', ['venue' => new Venue(['is_active' => true])]);
    }

    public function storeVenue(Request $request)
    {
        $venue = Venue::create($this->validateVenue($request));

        AdminAuditLog::record('venue.create', $venue, null, ['name' => $venue->name]);

        return redirect()->route('admin.activities.index')->with('success', $venue->name.' added.');
    }

    public function editVenue(Venue $venue)
    {
        return view('admin.activities.venue-form', ['venue' => $venue]);
    }

    public function updateVenue(Request $request, Venue $venue)
    {
        $venue->update($this->validateVenue($request));

        AdminAuditLog::record('venue.update', $venue, null, ['name' => $venue->name]);

        return redirect()->route('admin.activities.index')->with('success', $venue->name.' updated.');
    }

    /** Activities at a deleted venue keep going, just without a place. */
    public function destroyVenue(Venue $venue)
    {
        AdminAuditLog::record('venue.delete', $venue, null, [
            'name' => $venue->name,
            'activities' => $venue->activities()->count(),
        ]);

        $venue->activities()->update(['venue_id' => null]);
        $venue->delete();

        return redirect()->route('admin.activities.index')->with('success', $venue->name.' deleted.');
    }

    private function validateActivity(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'status' => ['required', Rule::in(array_keys(Activity::STATUSES))],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'starts_on' => ['nullable', 'date'],
            'schedule' => ['nullable', 'string', 'max:150'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'convenor_name' => ['nullable', 'string', 'max:150'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'link_label' => ['nullable', 'string', 'max:100'],
            'volunteer_note' => ['nullable', 'string', 'max:255'],
            // WhatsApp invite links only, so a mistyped or unrelated URL is
            // not handed to every parent who signs up.
            'whatsapp_url' => ['nullable', 'url', 'max:255', 'regex:#^https://(chat\.whatsapp\.com|wa\.me)/#'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'whatsapp_url.regex' => 'That should be a WhatsApp invite link, starting https://chat.whatsapp.com/',
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['suggestions_open'] = $request->boolean('suggestions_open');
        $validated['signups_open'] = $request->boolean('signups_open');
        $validated['volunteers_open'] = $request->boolean('volunteers_open');

        return $validated;
    }

    private function validateVenue(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'town' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'map_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
