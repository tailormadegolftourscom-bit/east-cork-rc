<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Supporter;
use App\Models\Parents;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Parents::with('school');

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($type = $request->string('user_type')->toString()) {
            $query->where('user_type', $type);

            // Filtering to "Parent" specifically means genuine, registered
            // parents (completed /parent/start) — not accounts still
            // mid-registration. The unfiltered "All roles" view still shows
            // everyone, since that's meant to be a complete account list.
            if ($type === 'parent') {
                $query->whereHas('supporter');
            }
        }

        $users = $query->orderBy('name')->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(Parents $user)
    {
        $user->load(['children', 'school']);

        $auditLog = AdminAuditLog::where('target_type', Parents::class)
            ->where('target_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.show', compact('user', 'auditLog'));
    }

    public function suspend(Request $request, Parents $user)
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

    public function reactivate(Request $request, Parents $user)
    {
        $user->forceFill(['suspended_at' => null])->save();

        AdminAuditLog::record('user.reactivate', $user);

        return back()->with('success', 'User reactivated.');
    }

    public function destroy(Request $request, Parents $user)
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

        // Identity and login are one row now, so deleting a parent takes
        // their details, their children and their supporter record with it
        // via the foreign keys — no orphan cleanup left to do by hand.
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
