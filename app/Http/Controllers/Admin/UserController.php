<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Supporter;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['person', 'school']);

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($type = $request->string('user_type')->toString()) {
            $query->where('user_type', $type);
        }

        $users = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['person.children', 'school']);

        $auditLog = AdminAuditLog::where('target_type', User::class)
            ->where('target_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.show', compact('user', 'auditLog'));
    }

    public function suspend(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $user->forceFill(['suspended_at' => now()])->save();

        AdminAuditLog::record('user.suspend', $user, $validated['reason']);

        return back()->with('success', 'User suspended.');
    }

    public function reactivate(Request $request, User $user)
    {
        $user->forceFill(['suspended_at' => null])->save();

        AdminAuditLog::record('user.reactivate', $user);

        return back()->with('success', 'User reactivated.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'confirm_email' => ['required', 'string'],
        ]);

        if (strcasecmp($validated['confirm_email'], $user->email) !== 0) {
            return back()->with('error', 'Confirmation email did not match. User was not deleted.');
        }

        AdminAuditLog::record('user.delete', $user, $validated['reason'], [
            'email' => $user->email,
            'user_type' => $user->user_type,
        ]);

        $personId = $user->person_id;

        $user->delete();

        // Deleting a user must never leave an active Supporter record behind
        // with no account able to log in and manage it — that's exactly the
        // kind of orphan that silently inflates the public "parents
        // registered" count. Only deactivate if no other user still owns
        // this person record.
        if ($personId && ! User::where('person_id', $personId)->exists()) {
            Supporter::where('person_id', $personId)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
