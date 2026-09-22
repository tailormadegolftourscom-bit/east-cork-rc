<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\WorkshopRsvp;
use Illuminate\Http\Request;

class WorkshopRsvpController extends Controller
{
    public function index()
    {
        $rsvps = WorkshopRsvp::orderBy('workshop')->orderBy('created_at')->get();

        $workshops = collect(config('notice.workshops', []))
            ->map(function (array $workshop, string $key) use ($rsvps) {
                $forThis = $rsvps->where('workshop', $key);

                return $workshop + [
                    'key' => $key,
                    'rsvps' => $forThis,
                    'attendees' => $forThis->sum('attendees'),
                ];
            });

        // People who could not make either evening, kept separate: they are a
        // reason to schedule a third night, not attendees of the first two.
        $alternatives = $rsvps->where('workshop', WorkshopRsvp::ALTERNATIVE);

        return view('admin.workshops.index', compact('workshops', 'rsvps', 'alternatives'));
    }

    public function destroy(Request $request, WorkshopRsvp $rsvp)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $name = $rsvp->name;

        AdminAuditLog::record('workshop_rsvp.delete', $rsvp, $validated['reason'], [
            'name' => $rsvp->name,
            'email' => $rsvp->email,
            'workshop' => $rsvp->workshop,
        ]);

        $rsvp->delete();

        return back()->with('success', $name.' removed from the list.');
    }
}
