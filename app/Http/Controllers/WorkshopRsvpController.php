<?php

namespace App\Http\Controllers;

use App\Mail\WorkshopRsvpMail;
use App\Models\WorkshopRsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * RSVPs for the October workshops.
 *
 * No account required. The evenings are aimed at parents who have not decided
 * anything yet, and asking them to register first would lose the people the
 * workshops exist to reach.
 */
class WorkshopRsvpController extends Controller
{
    public function create(Request $request, ?string $workshop = null)
    {
        $workshops = config('notice.workshops', []);

        return view('workshops.rsvp', [
            'workshops' => $workshops,
            'selected' => array_key_exists((string) $workshop, $workshops) ? $workshop : null,
            'venue' => config('notice.venue'),
        ]);
    }

    public function store(Request $request)
    {
        $workshops = config('notice.workshops', []);

        $validated = $request->validate([
            'workshop' => ['required', Rule::in(array_keys($workshops))],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['required', 'integer', 'min:0', 'max:20'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        // Submitting again updates rather than duplicating — people change
        // their numbers, and a second RSVP should not inflate the count.
        $rsvp = WorkshopRsvp::updateOrCreate(
            ['workshop' => $validated['workshop'], 'email' => mb_strtolower(trim($validated['email']))],
            [
                'name' => trim($validated['name']),
                'phone' => $validated['phone'] ?? null,
                'adults' => $validated['adults'],
                'children' => $validated['children'],
                'note' => $validated['note'] ?? null,
            ]
        );

        Mail::to($rsvp->email)->send(new WorkshopRsvpMail($rsvp));

        return redirect()
            ->route('workshops.rsvp', ['workshop' => $rsvp->workshop])
            ->with('success', 'Thanks — you are down for '.$rsvp->label().'. We have sent you a confirmation.');
    }
}
