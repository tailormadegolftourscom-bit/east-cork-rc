<?php

namespace App\Http\Controllers;

use App\Mail\CoParentInviteMail;
use App\Models\Child;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CoParentController extends Controller
{
    public function create()
    {
        return view('parent.co-parent.create');
    }

    public function store(Request $request)
    {
        $inviter = auth()->user()->person;

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $email = trim($validated['email']);

        if (Str::lower($email) === Str::lower((string) $inviter->email)) {
            return back()
                ->withErrors(['email' => 'You cannot invite yourself as a co-parent.'])
                ->withInput();
        }

        $existingUser = User::whereRaw('LOWER(email) = ?', [Str::lower($email)])->first();

        if ($existingUser && ($existingUser->user_type !== 'parent' || ! $existingUser->person_id)) {
            return back()
                ->withErrors(['email' => 'That email is already registered as a different type of account.'])
                ->withInput();
        }

        $isNewAccount = ! $existingUser;

        $targetPerson = DB::transaction(function () use ($existingUser, $validated, $email, $inviter) {
            if ($existingUser) {
                $targetPerson = $existingUser->person;
            } else {
                $targetPerson = Person::create([
                    'first_name' => trim($validated['first_name']),
                    'last_name' => trim($validated['last_name']),
                    'email' => $email,
                    'phone' => null,
                    'public_name_mode' => 'real_name',
                ]);

                // Verified immediately: the invite + password-reset link the
                // recipient must click to ever access the account already
                // proves ownership of the inbox, same as a school invite.
                User::create([
                    'person_id' => $targetPerson->id,
                    'name' => trim($validated['first_name'].' '.$validated['last_name']),
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'is_admin' => 0,
                    'user_type' => 'parent',
                    'school_id' => null,
                    'email_verified_at' => now(),
                ]);
            }

            $children = Child::where('parent_person_id', $inviter->id)->get();

            foreach ($children as $child) {
                $child->guardians()->syncWithoutDetaching([$targetPerson->id]);
            }

            return $targetPerson;
        });

        Mail::to($email)->send(new CoParentInviteMail($inviter, $isNewAccount));

        if ($isNewAccount) {
            Password::sendResetLink(['email' => $email]);
        }

        return redirect()
            ->route('parent.dashboard')
            ->with('success', $targetPerson->first_name.' has been added as a co-parent for your children.');
    }
}
