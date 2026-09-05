<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchoolPortalController extends Controller
{
    public function dashboard()
    {
        $school = auth()->user()->school;

        return view('school.dashboard', compact('school'));
    }

    public function edit()
    {
        $school = auth()->user()->school;

        return view('school.profile-edit', compact('school'));
    }

    public function update(Request $request)
    {
        $school = auth()->user()->school;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'town' => ['nullable', 'string', 'max:100'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:40'],
            'principal_name' => ['nullable', 'string', 'max:150'],
            'principal_email' => ['nullable', 'email', 'max:150'],
            'vice_principal_name' => ['nullable', 'string', 'max:150'],
            'vice_principal_email' => ['nullable', 'email', 'max:150'],
            'secretary_name' => ['nullable', 'string', 'max:150'],
            'secretary_email' => ['nullable', 'email', 'max:150'],
            'support_status' => ['required', 'in:undecided,supporting'],
            'notes' => ['nullable', 'string'],
        ]);

        $school->update($validated);

        return redirect()
            ->route('school.profile.edit')
            ->with('success', 'School details updated.');
    }
}
