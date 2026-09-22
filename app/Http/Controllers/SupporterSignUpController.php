<?php

namespace App\Http\Controllers;

use App\Mail\NewSupporterRegisteredMail;
use App\Models\Supporter;
use App\Models\SupporterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * Public sign-up for people who back the initiative but are not parents here.
 *
 * No account is created: a teacher with ideas or a neighbour offering to help
 * has no reason to need a login, and every extra step loses people at the
 * armchair end, which is where most of them are.
 */
class SupporterSignUpController extends Controller
{
    public function create()
    {
        return view('supporters.create', [
            'categories' => SupporterCategory::active()->ordered()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $categoryIds = SupporterCategory::active()->pluck('id');

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('supporters', 'email')],
            'phone' => ['nullable', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:2000'],
            'preferred_contact_method' => ['required', 'in:email,sms,whatsapp'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => [Rule::in($categoryIds)],
        ], [
            'categories.required' => 'Please tell us at least one way you would like to support.',
        ]);

        if (
            in_array($validated['preferred_contact_method'], ['sms', 'whatsapp'], true)
            && empty($validated['phone'] ?? null)
        ) {
            return back()
                ->withErrors(['phone' => 'A phone number is required if you choose regular text or WhatsApp.'])
                ->withInput();
        }

        $supporter = DB::transaction(function () use ($validated) {
            $supporter = Supporter::create([
                'first_name' => trim($validated['first_name']),
                'last_name' => trim($validated['last_name']),
                'email' => trim($validated['email']),
                'phone' => $validated['phone'] ?? null,
                'message' => $validated['message'] ?? null,
                'preferred_contact_method' => $validated['preferred_contact_method'],
                'is_active' => true,
                'joined_at' => now(),
            ]);

            $supporter->categories()->sync($validated['categories']);

            return $supporter;
        });

        Mail::to(config('mail.oversight_bcc'))->send(new NewSupporterRegisteredMail($supporter));

        return redirect()
            ->route('supporters.create')
            ->with('success', 'Thank you — you have been added as a supporter. We will be in touch.');
    }
}
