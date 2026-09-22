<?php

namespace App\Http\Controllers;

use App\Mail\AddedToCommitteeMail;
use App\Models\Committee;
use App\Models\CommitteeMember;
use App\Models\Parents;
use App\Models\Supporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Joining, convening and running a committee.
 *
 * Only parents act for themselves: the supporters register has no logins by
 * design, so supporters are added by a committee's convenor instead.
 */
class CommitteeMembershipController extends Controller
{
    /**
     * Step forward for a committee nobody is running yet.
     */
    public function becomeConvenor(Request $request, Committee $committee)
    {
        $parent = $this->actingParent();

        if ($committee->hasConvenor()) {
            return back()->with('error', 'Someone became convenor of this committee first. You can still join it.');
        }

        $this->guardNameConsent($request, $parent);

        DB::transaction(function () use ($committee, $parent) {
            // Already a member who is stepping up, or brand new to it.
            $existing = $committee->membershipFor($parent);

            if ($existing) {
                $existing->update(['role' => 'convenor', 'name_consent_at' => now()]);

                return;
            }

            CommitteeMember::create([
                'committee_id' => $committee->id,
                'member_type' => Parents::class,
                'member_id' => $parent->id,
                'role' => 'convenor',
                'name_consent_at' => now(),
                'joined_at' => now(),
            ]);
        });

        return back()->with('success', 'You are now the convenor of '.$committee->name.'.');
    }

    public function join(Request $request, Committee $committee)
    {
        $parent = $this->actingParent();

        if ($committee->membershipFor($parent)) {
            return back()->with('error', 'You are already on this committee.');
        }

        $this->guardNameConsent($request, $parent);

        CommitteeMember::create([
            'committee_id' => $committee->id,
            'member_type' => Parents::class,
            'member_id' => $parent->id,
            'role' => 'member',
            'name_consent_at' => now(),
            'joined_at' => now(),
        ]);

        return back()->with('success', 'You have joined '.$committee->name.'.');
    }

    public function leave(Request $request, Committee $committee)
    {
        $parent = $this->actingParent();
        $membership = $committee->membershipFor($parent);

        if (! $membership) {
            return back()->with('error', 'You are not on this committee.');
        }

        // A convenor leaving would strand the members with nobody running it,
        // so hand over first — or clear the committee out entirely.
        if ($membership->isConvenor() && $committee->members->count() > 1) {
            return back()->with('error',
                'You are the convenor. Hand over to another member before leaving, '
                .'so the committee is not left without one.'
            );
        }

        $membership->delete();

        return back()->with('success', 'You have left '.$committee->name.'.');
    }

    /**
     * The convenor adds anyone from either register — this is the only route
     * by which a non-parent supporter gets onto a committee.
     */
    public function addMember(Request $request, Committee $committee)
    {
        $this->guardConvenor($committee);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        $email = Str::lower(trim($validated['email']));

        $person = Parents::whereRaw('LOWER(email) = ?', [$email])->where('user_type', 'parent')->first()
            ?: Supporter::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $person) {
            return back()->with('error',
                'No parent or supporter found with that email. Ask them to register first at /support, '
                .'or add their details yourself from the Supporters screen.'
            );
        }

        if ($committee->membershipFor($person)) {
            return back()->with('error', 'They are already on this committee.');
        }

        CommitteeMember::create([
            'committee_id' => $committee->id,
            'member_type' => $person::class,
            'member_id' => $person->id,
            'role' => 'member',
            // The convenor added them, so they have not consented to being
            // named themselves — their own display setting stands until they
            // say otherwise.
            'name_consent_at' => null,
            'joined_at' => now(),
        ]);

        Mail::to($person->email)->send(new AddedToCommitteeMail($person, $committee));

        return back()->with('success', $person->full_name.' has been added and told by email.');
    }

    public function removeMember(Request $request, Committee $committee, CommitteeMember $member)
    {
        $this->guardConvenor($committee);

        abort_unless($member->committee_id === $committee->id, 404);

        if ($member->isConvenor()) {
            return back()->with('error', 'Hand the role over rather than removing yourself here.');
        }

        $name = $member->display_name;
        $member->delete();

        return back()->with('success', $name.' has been removed from the committee.');
    }

    /** Pass the role to an existing member and step back to ordinary membership. */
    public function handOver(Request $request, Committee $committee, CommitteeMember $member)
    {
        $this->guardConvenor($committee);

        abort_unless($member->committee_id === $committee->id, 404);

        $parent = $this->actingParent();
        $current = $committee->membershipFor($parent);

        DB::transaction(function () use ($current, $member) {
            $current->update(['role' => 'member']);
            $member->update(['role' => 'convenor']);
        });

        return back()->with('success', $member->display_name.' is now the convenor.');
    }

    /**
     * Agree, after the fact, to be named here.
     *
     * Someone put on a committee by a convenor or an admin never consented to
     * being named, so they show under their anonymous code. This lets them
     * fix that themselves for this committee without changing how they appear
     * everywhere else.
     */
    public function consentToNaming(Request $request, Committee $committee)
    {
        $parent = $this->actingParent();
        $membership = $committee->membershipFor($parent);

        if (! $membership) {
            return back()->with('error', 'You are not on this committee.');
        }

        $membership->update(['name_consent_at' => now()]);

        return back()->with('success', 'Your name is now shown on this committee.');
    }

    public function updateObjectives(Request $request, Committee $committee)
    {
        $this->guardConvenor($committee);

        $validated = $request->validate([
            'objectives' => ['nullable', 'string', 'max:5000'],
        ]);

        $committee->update(['objectives' => $validated['objectives'] ?? null]);

        return back()->with('success', 'Objectives updated.');
    }

    private function actingParent(): Parents
    {
        $parent = auth()->user();

        abort_unless($parent && $parent->user_type === 'parent', 403);

        return $parent;
    }

    private function guardConvenor(Committee $committee): void
    {
        $membership = $committee->membershipFor(auth()->user());

        abort_unless($membership && $membership->isConvenor(), 403);
    }

    /**
     * Committee lists name people. A parent who appears publicly under an
     * anonymous code has to agree to that before joining, rather than having
     * it decided for them.
     */
    private function guardNameConsent(Request $request, Parents $parent): void
    {
        if ($parent->public_name_mode !== 'anon_code') {
            return;
        }

        $request->validate([
            'name_consent' => ['accepted'],
        ], [
            'name_consent.accepted' => 'Committee members are listed by name. '
                .'Please confirm you are happy to be named before joining.',
        ]);
    }
}
