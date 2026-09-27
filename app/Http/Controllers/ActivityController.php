<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivitySignup;
use App\Models\ActivityVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * An activity's own page: details, signing children up, volunteering, and —
 * once a parent has signed up — the activity's WhatsApp group link.
 */
class ActivityController extends Controller
{
    public function show(Request $request, Activity $activity)
    {
        abort_unless($activity->is_active, 404);

        $user = $request->user();
        $isParent = $user && $user->user_type === 'parent';
        $children = $isParent ? $this->childrenOf($user) : collect();

        $signedUpIds = $isParent
            ? $activity->signups()->whereIn('child_id', $children->pluck('id'))->pluck('child_id')
            : collect();

        $volunteered = $isParent && $activity->volunteers()->where('parent_id', $user->id)->exists();

        return view('activities.show', [
            'activity' => $activity->load('venue'),
            'isParent' => $isParent,
            'children' => $children,
            'signedUpIds' => $signedUpIds,
            'signupCount' => $activity->signups()->count(),
            'volunteered' => $volunteered,
            // The WhatsApp link is for people taking part, not the public:
            // everyone in the group sees everyone's number.
            'showWhatsapp' => $activity->whatsapp_url && ($signedUpIds->isNotEmpty() || $volunteered),
        ]);
    }

    /** "Log In" from an activity page: remember the page, then log in. */
    public function logIn(Request $request, Activity $activity)
    {
        $request->session()->put('url.intended', route('activities.show', $activity));

        return redirect()->route('login');
    }

    /** Ticks and unticks this parent's children for the activity. */
    public function signUp(Request $request, Activity $activity)
    {
        abort_unless($activity->is_active && $activity->signups_open && ! $activity->isPast(), 404);

        $user = $request->user();
        $childIds = $this->childrenOf($user)->pluck('id');

        $validated = $request->validate([
            'children' => ['array'],
            'children.*' => [Rule::in($childIds->all())],
        ]);

        $chosen = collect($validated['children'] ?? [])->map(fn ($id) => (int) $id);

        DB::transaction(function () use ($activity, $childIds, $chosen, $user) {
            // Only this parent's children are touched; a co-parent's choices
            // for the same child are the same rows, which is right.
            $activity->signups()->whereIn('child_id', $childIds)->whereNotIn('child_id', $chosen)->delete();

            foreach ($chosen as $childId) {
                ActivitySignup::firstOrCreate(
                    ['activity_id' => $activity->id, 'child_id' => $childId],
                    ['parent_id' => $user->id]
                );
            }
        });

        $message = $chosen->isEmpty()
            ? 'Your children are no longer signed up for '.$activity->title.'.'
            : 'Signed up for '.$activity->title.'.'.($activity->whatsapp_url ? ' The WhatsApp group link is below.' : '');

        return redirect()->route('activities.show', $activity)->with('success', $message);
    }

    public function volunteer(Request $request, Activity $activity)
    {
        abort_unless($activity->is_active && $activity->volunteers_open && ! $activity->isPast(), 404);

        // Hidden field people never see; bots fill it in.
        if ($request->filled('website')) {
            return redirect()->route('activities.show', $activity);
        }

        $user = $request->user();
        $isParent = $user && $user->user_type === 'parent';

        $validated = $request->validate([
            'name' => [$isParent ? 'nullable' : 'required', 'string', 'max:150'],
            'email' => [$isParent ? 'nullable' : 'required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'phone.required' => 'A phone number is needed so the convenor can reach you on the day.',
        ]);

        $email = $isParent ? $user->email : trim($validated['email']);

        ActivityVolunteer::updateOrCreate(
            ['activity_id' => $activity->id, 'email' => $email],
            [
                'parent_id' => $isParent ? $user->id : null,
                'name' => $isParent ? $user->full_name : trim($validated['name']),
                'phone' => trim($validated['phone']),
                'note' => $validated['note'] ?? null,
            ]
        );

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Thank you for volunteering. The convenor will be in touch before the day.');
    }

    /** Children this parent owns or co-parents. */
    private function childrenOf($parent): Collection
    {
        return $parent->children()->get()
            ->merge($parent->guardianOfChildren()->get())
            ->unique('id')
            ->sortBy('first_name')
            ->values();
    }
}
