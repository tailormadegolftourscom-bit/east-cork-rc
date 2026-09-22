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
        $parent = auth()->user();
        $supporter = Supporter::where('person_id', $parent->id)->first();

        return view('parent.start', compact('parent', 'supporter'));
    }

    public function update(Request $request)
    {
        $parent = auth()->user();

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

        $parent->update([
            'preferred_contact_method' => $validated['preferred_contact_method'],
            'phone' => $validated['phone'] ?? null,
            'public_name_mode' => $validated['public_name_mode'],
            'phone_verified_at' => $validated['preferred_contact_method'] === 'email'
                ? null
                : $parent->phone_verified_at,
        ]);

        $supporter = Supporter::updateOrCreate(
            ['person_id' => $parent->id],
            [
                'support_status' => 'supporting',
                'is_active' => 1,
            ]
        );

        if ($supporter->wasRecentlyCreated) {
            Mail::to(config('mail.oversight_bcc'))->send(new NewSupporterMail($parent));
        }

        return redirect()
            ->route('parent.welcome')
            ->with('success', 'Your details have been saved.');
    }

    public function welcome()
    {
        $parent = auth()->user();
        $supporter = $parent->supporter;

        return view('parent.welcome', compact('parent', 'supporter'));
    }

    public function dashboard()
    {
        $parent = auth()->user();
        $supporter = $parent->supporter;
        $children = Child::where('parent_id', $parent->id)
            ->orWhereHas('guardians', fn ($q) => $q->where('parents.id', $parent->id))
            ->with([
                'schoolLink.currentSchool' => fn ($q) => $q->withCount('childLinks as registered_children_count'),
                'schoolLink.currentSchoolClass' => fn ($q) => $q->withCount('childLinks as registered_children_count'),
                'owner',
            ])
            ->latest()
            ->get();

        return view('parent.dashboard', compact('parent', 'supporter', 'children'));
    }

    public function editProfile()
    {
        $parent = auth()->user();
        $supporter = $parent->supporter;

        return view('parent.profile', compact('parent', 'supporter'));
    }

    public function updateProfile(Request $request)
    {
        $parent = auth()->user();

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

        $parent->update([
            'preferred_contact_method' => $validated['preferred_contact_method'],
            'phone' => $validated['phone'] ?? null,
            'public_name_mode' => $validated['public_name_mode'],
            'phone_verified_at' => $validated['preferred_contact_method'] === 'email'
                ? null
                : $parent->phone_verified_at,
        ]);

        return redirect()
            ->route('parent.dashboard')
            ->with('success', 'Your profile has been updated.');
    }
}
