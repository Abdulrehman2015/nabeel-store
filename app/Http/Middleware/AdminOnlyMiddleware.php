<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyMiddleware
{
    /**
     * Handle an incoming request: ensure user is an Admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            if ($request->expectsJson() || $request->isMethod('DELETE') || $request->isMethod('POST') || $request->isMethod('PUT')) {
                abort(403, 'Unauthorized. This action is restricted to store administrators.');
            }

            return redirect()->route('admin.pos.index')->with('error', 'Access denied. You do not have permission to view that administrative section.');
        }

        return $next($request);
    }
}

