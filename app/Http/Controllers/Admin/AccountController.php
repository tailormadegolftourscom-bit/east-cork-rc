<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use Illuminate\Http\Request;

/**
 * Admin and school logins. They share the `parents` table because Laravel
 * authenticates against exactly one table, but they are not parents and do
 * not belong on that screen.
 */
class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Parents::with('school')
            ->whereIn('user_type', ['admin', 'school'])
            ->orderBy('user_type')
            ->orderBy('email')
            ->paginate(25);

        return view('admin.accounts.index', compact('accounts'));
    }
}
