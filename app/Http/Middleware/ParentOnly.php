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

        if (! $user->person_id) {
            abort(403);
        }

        return $next($request);
    }
}
