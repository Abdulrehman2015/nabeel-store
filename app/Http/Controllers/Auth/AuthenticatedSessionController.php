<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Security Check 1: Account Active Status
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $contact = $this->otpService->getAdminContact();

            throw ValidationException::withMessages([
                'email' => "This account has been blocked. Please contact the administrator at {$contact['email']}" . ($contact['phone'] ? " or {$contact['phone']}" : "") . " for assistance.",
            ]);
        }

        // Security Check 2: OTP Verification for Customer Accounts
        if (! $user->isAdmin() && ! $user->isStaff() && is_null($user->email_verified_at)) {
            // Generate OTP for verification
            try {
                $this->otpService->generate($user->email, 'email_verification', $user);
            } catch (\Throwable $e) {
                // cooldown may apply
            }

            session(['pending_verification_email' => $user->email]);

            return redirect()->route('otp.verify.notice')
                ->with('status', 'Please enter your 6-digit verification code to complete sign in.');
        }

        if ($user->isAdmin() || $user->isStaff()) {
            if ($user->isCashier()) {
                return redirect()->intended(route('admin.pos.index'));
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
