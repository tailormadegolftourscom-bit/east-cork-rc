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
            'selected' => (array_key_exists((string) $workshop, $workshops) || $workshop === WorkshopRsvp::ALTERNATIVE)
                ? $workshop
                : null,
            'venue' => config('notice.venue'),
        ]);
    }

    public function store(Request $request)
    {
        $workshops = config('notice.workshops', []);

        $validated = $request->validate([
            'workshop' => ['required', Rule::in([...array_keys($workshops), WorkshopRsvp::ALTERNATIVE])],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'attendees' => ['required', 'integer', 'min:1', 'max:20'],
            'note' => ['nullable', 'string', 'max:2000'],
        ], [
            'workshop.required' => 'Please choose an evening, or tell us neither suits.',
        ]);

        // Asking for another evening without saying when is a dead end for
        // whoever has to arrange it.
        if ($validated['workshop'] === WorkshopRsvp::ALTERNATIVE && empty($validated['note'] ?? null)) {
            return back()
                ->withInput()
                ->withErrors(['note' => 'Let us know roughly which evenings would suit you.']);
        }

        // Submitting again updates rather than duplicating — people change
        // their numbers, and a second RSVP should not inflate the count.
        $rsvp = WorkshopRsvp::updateOrCreate(
            ['workshop' => $validated['workshop'], 'email' => mb_strtolower(trim($validated['email']))],
            [
                'name' => trim($validated['name']),
                'phone' => $validated['phone'] ?? null,
                'attendees' => $validated['attendees'],
                'note' => $validated['note'] ?? null,
            ]
        );

        Mail::to($rsvp->email)->send(new WorkshopRsvpMail($rsvp));

        return redirect()
            ->route('workshops.rsvp', ['workshop' => $rsvp->workshop])
            ->with('success', $rsvp->isAlternative()
                ? 'Thanks — we have noted that neither evening suits, and what would. If enough people say the same we will arrange another.'
                : 'Thanks — you are down for '.$rsvp->label().'. We have sent you a confirmation.');
    }
}
