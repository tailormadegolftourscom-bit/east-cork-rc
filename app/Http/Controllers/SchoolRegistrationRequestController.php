<?php

namespace App\Http\Controllers;

use App\Models\SchoolRegistrationRequest;
use Illuminate\Http\Request;

class SchoolRegistrationRequestController extends Controller
{
    public function create()
    {
        return view('school-registration.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:150'],
            'school_type' => ['required', 'in:primary,secondary'],
            'town' => ['nullable', 'string', 'max:100'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'contact_name' => ['required', 'string', 'max:150'],
            'contact_email' => ['required', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['request_status'] = 'pending';

        SchoolRegistrationRequest::create($validated);

        return redirect()
            ->route('school-registration.create')
            ->with('success', 'Your school request has been submitted for review.');
    }
}
