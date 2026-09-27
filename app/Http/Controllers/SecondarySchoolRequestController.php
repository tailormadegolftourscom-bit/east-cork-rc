<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolRegistrationRequest;
use Illuminate\Http\Request;

/**
 * "Request it be added as a secondary school", from the child form.
 *
 * Files an ordinary school registration request, so it lands in the same
 * admin queue as /add-my-school. Answers in JSON because the button sits in
 * the middle of the child form and must not throw away what is typed there.
 */
class SecondarySchoolRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:150'],
            'town' => ['nullable', 'string', 'max:100'],
        ], [
            'school_name.required' => 'Type the school\'s name first.',
        ]);

        $name = trim($validated['school_name']);
        $lower = mb_strtolower($name);

        $listed = School::where('school_type', 'secondary')
            ->whereRaw('LOWER(name) = ?', [$lower])
            ->first();

        if ($listed) {
            return response()->json([
                'message' => $listed->name.' is already listed — choose it from the list above.',
            ], 422);
        }

        // One request per school is enough; a second parent asking for the
        // same one is told it is already on its way rather than queued twice.
        $pending = SchoolRegistrationRequest::where('school_type', 'secondary')
            ->where('request_status', 'pending')
            ->whereRaw('LOWER(school_name) = ?', [$lower])
            ->exists();

        if ($pending) {
            return response()->json([
                'message' => 'Thanks — '.$name.' has already been requested and is waiting to be added. '
                    .'Save this form with Unlisted chosen and your child will be linked to it once it is.',
            ]);
        }

        $parent = $request->user();

        SchoolRegistrationRequest::create([
            'school_name' => $name,
            'school_type' => 'secondary',
            'town' => ($validated['town'] ?? null) ?: null,
            'contact_name' => $parent->full_name,
            'contact_email' => $parent->email,
            'notes' => 'Requested by a parent registering a 6th class child, as the secondary school the child intends to go to.',
            'request_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Requested — we\'ll add '.$name.' as a secondary school. Save this form with Unlisted '
                .'chosen and your child will be linked to it once it\'s added.',
        ]);
    }
}
