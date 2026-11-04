<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerEmailIsVerified
{
    /**
     * Prevent unverified customer accounts from accessing protected areas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isAdmin() && ! $user->isStaff() && is_null($user->email_verified_at)) {
            session(['pending_verification_email' => $user->email]);
            session()->put('url.intended', $request->fullUrl());

            return redirect()->route('otp.verify.notice')
                ->with('warning', 'Please verify your email before accessing your account.');
        }

        return $next($request);
    }
}
