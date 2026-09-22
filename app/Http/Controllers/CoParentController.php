<?php

namespace App\Http\Controllers;

use App\Mail\CoParentInviteMail;
use App\Models\Child;
use App\Models\Parents;
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
        $inviter = auth()->user();

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

        $existingUser = Parents::whereRaw('LOWER(email) = ?', [Str::lower($email)])->first();

        if ($existingUser && $existingUser->user_type !== 'parent') {
            return back()
                ->withErrors(['email' => 'That email is already registered as a different type of account.'])
                ->withInput();
        }

        $isNewAccount = ! $existingUser;

        $target = DB::transaction(function () use ($existingUser, $validated, $email, $inviter) {
            $first = trim($validated['first_name']);
            $last = trim($validated['last_name']);

            // Verified immediately: the invite + password-reset link the
            // recipient must click to ever access the account already
            // proves ownership of the inbox, same as a school invite.
            //
            // registration_completed_at is deliberately left null — an invite
            // nobody has answered is not a finished registration, and the
            // 3/6/9 sweep relies on that distinction.
            $target = $existingUser ?: Parents::create([
                'first_name' => $first,
                'last_name' => $last,
                'name' => $first.' '.$last,
                'email' => $email,
                'phone' => null,
                'public_name_mode' => 'real_name',
                'password' => Hash::make(Str::random(32)),
                'is_admin' => 0,
                'user_type' => 'parent',
                'school_id' => null,
                'email_verified_at' => now(),
                'invited_at' => now(),
            ]);

            $children = Child::where('parent_id', $inviter->id)->get();

            foreach ($children as $child) {
                $child->guardians()->syncWithoutDetaching([$target->id]);
            }

            return $target;
        });

        Mail::to($email)->send(new CoParentInviteMail($inviter, $isNewAccount));

        if ($isNewAccount) {
            Password::sendResetLink(['email' => $email]);
        }

        return redirect()
            ->route('parent.dashboard')
            ->with('success', $target->first_name.' has been added as a co-parent for your children.');
    }
}
