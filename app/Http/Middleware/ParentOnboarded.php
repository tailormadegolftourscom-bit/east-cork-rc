<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        $parent = $request->user();

        if (! $parent || ! $parent->supporter) {
            return redirect()
                ->route('parent.start')
                ->with('error', 'Please finish setting up your account first.');
        }

        return $next($request);
    }
}
