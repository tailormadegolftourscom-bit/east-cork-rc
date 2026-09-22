<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Child;
use App\Models\Parents;
use App\Models\SupporterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * The single Parents screen — replaces the old split between "Users" and
 * "Supporters & Children", which showed the same people twice under two
 * different names.
 */
class ParentController extends Controller
{
    public function index(Request $request)
    {
        $query = Parents::with(['children', 'categories'])
            ->where('user_type', 'parent');

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        match ($request->string('state')->toString()) {
            // Finished onboarding — the ones the public count is built from.
            'registered' => $query->whereNotNull('onboarded_at'),
            // Started but never finished: invited co-parents who have not
            // answered, and abandoned sign-ups. These are what the 3/6/9
            // sweep chases.
            'pending' => $query->whereNull('onboarded_at'),
            'no_children' => $query->whereNotNull('onboarded_at')->whereDoesntHave('children'),
            default => null,
        };

        $parents = $query->orderBy('last_name')->orderBy('first_name')->paginate(25)->withQueryString();

        return view('admin.parents.index', [
            'parents' => $parents,
            'counts' => [
                'all' => Parents::where('user_type', 'parent')->count(),
                'registered' => Parents::where('user_type', 'parent')->whereNotNull('onboarded_at')->count(),
                'pending' => Parents::where('user_type', 'parent')->whereNull('onboarded_at')->count(),
            ],
        ]);
    }

    public function show(Parents $parent)
    {
        $parent->load([
            'categories',
            'children.schoolLink.currentSchool',
            'children.schoolLink.currentSchoolClass',
            'guardianOfChildren.owner',
            'school',
        ]);

        $auditLog = AdminAuditLog::where('target_type', Parents::class)
            ->where('target_id', $parent->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.parents.show', [
            'parent' => $parent,
            'auditLog' => $auditLog,
            'categories' => SupporterCategory::active()->ordered()->get(),
        ]);
    }

    public function updateCategories(Request $request, Parents $parent)
    {
        $validated = $request->validate([
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:supporter_categories,id'],
        ]);

        $parent->categories()->sync($validated['categories'] ?? []);

        return back()->with('success', 'Support categories updated.');
    }

    /**
     * Send a fresh invite and restart the 3/6/9 clock. Without this, an
     * invite that went stale weeks ago is deleted on the next sweep with no
     * chance to answer it.
     */
    public function resendInvite(Request $request, Parents $parent)
    {
        if ($parent->hasCompletedRegistration()) {
            return back()->with('error', 'That parent has already set a password — there is nothing to resend.');
        }

        Password::sendResetLink(['email' => $parent->email]);

        $parent->forceFill([
            'invited_at' => now(),
            'pending_reminder_count' => 0,
            'pending_reminder_sent_at' => null,
        ])->save();

        AdminAuditLog::record('parent.reinvite', $parent, 'Fresh invite sent; 3/6/9 clock restarted.');

        return back()->with('success', 'Invite resent to '.$parent->email.'. Their clock starts again today.');
    }

    public function suspend(Request $request, Parents $parent)
    {
        if ($parent->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $parent->forceFill(['suspended_at' => now()])->save();

        AdminAuditLog::record('parent.suspend', $parent, $validated['reason']);

        return back()->with('success', 'Parent suspended.');
    }

    public function reactivate(Request $request, Parents $parent)
    {
        $parent->forceFill(['suspended_at' => null])->save();

        AdminAuditLog::record('parent.reactivate', $parent);

        return back()->with('success', 'Parent reactivated.');
    }

    public function destroy(Request $request, Parents $parent)
    {
        if ($parent->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'confirm_email' => ['required', 'string'],
        ]);

        if (strcasecmp($validated['confirm_email'], $parent->email) !== 0) {
            return back()->with('error', 'Confirmation email did not match. Nothing was deleted.');
        }

        // Deleting the owner of a child a co-parent also looks after takes
        // that child with it, and the co-parent finds an empty dashboard with
        // no warning. Make the admin move ownership first.
        $strandedChildren = $parent->children()
            ->whereHas('guardians', fn ($q) => $q->where('parents.id', '!=', $parent->id))
            ->pluck('first_name');

        if ($strandedChildren->isNotEmpty()) {
            return back()->with('error',
                'This parent owns '.$strandedChildren->join(', ').', who a co-parent also looks after. '
                .'Transfer or delete those children first — deleting now would remove them from the co-parent too.'
            );
        }

        AdminAuditLog::record('parent.delete', $parent, $validated['reason'], [
            'email' => $parent->email,
            'user_type' => $parent->user_type,
        ]);

        // One row per human, so this takes their details, children, guardian
        // links and category tags with it via the foreign keys.
        $parent->delete();

        return redirect()
            ->route('admin.parents.index')
            ->with('success', 'Parent deleted.');
    }

    public function updateChildAuditStatus(Request $request, Child $child)
    {
        $validated = $request->validate([
            'audit_status' => ['required', 'in:pending,reviewed,verified'],
        ]);

        $child->update($validated);

        return back()->with('success', $child->first_name.'\'s audit status updated.');
    }

    public function destroyChild(Request $request, Child $child)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $name = $child->first_name;

        AdminAuditLog::record('child.delete', $child, $validated['reason'], [
            'first_name' => $child->first_name,
            'parent_id' => $child->parent_id,
        ]);

        $child->delete();

        return back()->with('success', $name.' has been removed.');
    }
}
