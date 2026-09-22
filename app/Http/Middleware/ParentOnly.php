<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if ($user->user_type !== 'parent') {
            abort(403);
        }

        // The old "is this login linked to a person record?" check is gone:
        // identity and login are the same row now, so a parent account
        // cannot exist without its own details.

        return $next($request);
    }
}
