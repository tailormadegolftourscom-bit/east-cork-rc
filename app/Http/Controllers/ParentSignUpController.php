<?php

namespace App\Http\Controllers;

use App\Mail\NewSupporterMail;
use App\Models\Child;
use App\Models\Supporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ParentSignUpController extends Controller
{
    public function edit()
    {
        $person = auth()->user()->person;
        $supporter = Supporter::where('person_id', $person->id)->first();

        return view('parent.start', compact('person', 'supporter'));
    }

    public function update(Request $request)
    {
        $person = auth()->user()->person;

        $validated = $request->validate([
            'preferred_contact_method' => ['required', 'in:email,sms,whatsapp'],
            'phone' => ['nullable', 'string', 'max:40'],
            'public_name_mode' => ['required', 'in:real_name,anon_code'],
        ]);

        if (
            in_array($validated['preferred_contact_method'], ['sms', 'whatsapp'], true)
            && empty($validated['phone'] ?? null)
        ) {
            return back()
                ->withErrors([
                    'phone' => 'A phone number is required if you choose regular text or WhatsApp.',
                ])
                ->withInput();
        }

        $person->update([
            'preferred_contact_method' => $validated['preferred_contact_method'],
            'phone' => $validated['phone'] ?? null,
            'public_name_mode' => $validated['public_name_mode'],
            'phone_verified_at' => $validated['preferred_contact_method'] === 'email'
                ? null
                : $person->phone_verified_at,
        ]);

        $supporter = Supporter::updateOrCreate(
            ['person_id' => $person->id],
            [
                'support_status' => 'supporting',
                'is_active' => 1,
            ]
        );

        if ($supporter->wasRecentlyCreated) {
            Mail::to(config('mail.oversight_bcc'))->send(new NewSupporterMail($person));
        }

        return redirect()
            ->route('parent.welcome')
            ->with('success', 'Your details have been saved.');
    }

    public function welcome()
    {
        $person = auth()->user()->person;
        $supporter = $person->supporter;

        return view('parent.welcome', compact('person', 'supporter'));
    }

    public function dashboard()
    {
        $person = auth()->user()->person;
        $supporter = $person->supporter;
        $children = Child::where('parent_person_id', $person->id)
            ->orWhereHas('guardians', fn ($q) => $q->where('people.id', $person->id))
            ->with([
                'schoolLink.currentSchool' => fn ($q) => $q->withCount('childLinks as registered_children_count'),
                'schoolLink.currentSchoolClass' => fn ($q) => $q->withCount('childLinks as registered_children_count'),
                'parentPerson',
            ])
            ->latest()
            ->get();

        return view('parent.dashboard', compact('person', 'supporter', 'children'));
    }

    public function editProfile()
    {
        $person = auth()->user()->person;
        $supporter = $person->supporter;

        return view('parent.profile', compact('person', 'supporter'));
    }

    public function updateProfile(Request $request)
    {
        $person = auth()->user()->person;

        $validated = $request->validate([
            'preferred_contact_method' => ['required', 'in:email,sms,whatsapp'],
            'phone' => ['nullable', 'string', 'max:40'],
            'public_name_mode' => ['required', 'in:real_name,anon_code'],
        ]);

        if (
            in_array($validated['preferred_contact_method'], ['sms', 'whatsapp'], true)
            && empty($validated['phone'] ?? null)
        ) {
            return back()
                ->withErrors([
                    'phone' => 'A phone number is required if you choose regular text or WhatsApp.',
                ])
                ->withInput();
        }

        $person->update([
            'preferred_contact_method' => $validated['preferred_contact_method'],
            'phone' => $validated['phone'] ?? null,
            'public_name_mode' => $validated['public_name_mode'],
            'phone_verified_at' => $validated['preferred_contact_method'] === 'email'
                ? null
                : $person->phone_verified_at,
        ]);

        return redirect()
            ->route('parent.dashboard')
            ->with('success', 'Your profile has been updated.');
    }
}
