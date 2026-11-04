<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Show the OTP verification form for logged in unverified user or pending session.
     */
    public function showNotice(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $email = $user ? $user->email : session('pending_verification_email') ?? session('blocked_email');

        if (! $email) {
            return redirect()->route('login')->with('warning', 'Please sign in to verify your account.');
        }

        $contact = $this->otpService->getAdminContact();
        $dbUser = User::where('email', strtolower($email))->first();
        $isBlocked = ($user && ! $user->is_active) || ($dbUser && ! $dbUser->is_active) || session('account_blocked');

        if ($isBlocked) {
            if (Auth::check()) {
                Auth::logout();
            }
            return view('auth.verify-otp', [
                'email' => $email,
                'isReset' => false,
                'isBlocked' => true,
                'adminEmail' => $contact['email'],
                'adminPhone' => $contact['phone'],
            ]);
        }

        if ($user && $user->email_verified_at) {
            return redirect()->route('home');
        }

        // If no active OTP exists, generate one automatically
        $latest = \App\Models\OtpCode::where('identifier', strtolower($email))
            ->where('type', 'email_verification')
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $latest || $latest->isExpired()) {
            try {
                $this->otpService->generate($email, 'email_verification', $user);
            } catch (\Throwable $e) {
                // cooldown may be active
            }
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'isReset' => false,
            'isBlocked' => false,
            'adminEmail' => $contact['email'],
            'adminPhone' => $contact['phone'],
        ]);
    }

    /**
     * Verify the entered 6-digit OTP code for email verification.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.regex' => 'The verification code must be exactly 6 digits.',
            'otp.required' => 'Please enter the 6-digit verification code.',
        ]);

        $user = Auth::user();
        $email = $user ? $user->email : session('pending_verification_email') ?? session('blocked_email');

        if (! $email) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please sign in again.']);
        }

        try {
            $this->otpService->verify($email, $request->otp, 'email_verification');
        } catch (ValidationException $e) {
            $dbUser = User::where('email', strtolower($email))->first();
            if (($dbUser && ! $dbUser->is_active) || session('account_blocked')) {
                Auth::logout();
            }
            throw $e;
        }

        if ($user) {
            $user->email_verified_at = now();
            $user->save();
        } else {
            $dbUser = User::where('email', strtolower($email))->first();
            if ($dbUser) {
                $dbUser->email_verified_at = now();
                $dbUser->save();
                Auth::login($dbUser);
                $request->session()->regenerate();
            }
        }

        session()->forget([
            'pending_verification_email',
            'dev_otp_code',
            'account_blocked',
            'blocked_email',
            'blocked_admin_email',
            'blocked_admin_phone',
            'otp_failed_resend_required',
        ]);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('status', 'Account successfully verified! Welcome to 99 Bulk Sales.');
    }

    /**
     * Resend a fresh 6-digit OTP.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $email = $user ? $user->email : session('pending_verification_email') ?? session('reset_password_email') ?? session('blocked_email');

        if (! $email) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please start over.']);
        }

        $type = session()->has('reset_password_email') ? 'password_reset' : 'email_verification';

        try {
            $this->otpService->generate($email, $type, $user);
            return back()->with('status', 'A new 6-digit verification code has been dispatched.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Show the OTP verification form for Password Reset.
     */
    public function showResetOtpNotice(Request $request): View|RedirectResponse
    {
        $email = session('reset_password_email') ?? session('blocked_email');
        if (! $email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please enter your account email first.']);
        }

        $contact = $this->otpService->getAdminContact();
        $dbUser = User::where('email', strtolower($email))->first();
        $isBlocked = ($dbUser && ! $dbUser->is_active) || session('account_blocked');

        if ($isBlocked) {
            return view('auth.verify-otp', [
                'email' => $email,
                'isReset' => true,
                'isBlocked' => true,
                'adminEmail' => $contact['email'],
                'adminPhone' => $contact['phone'],
            ]);
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'isReset' => true,
            'isBlocked' => false,
            'adminEmail' => $contact['email'],
            'adminPhone' => $contact['phone'],
        ]);
    }

    /**
     * Verify the OTP code for password reset.
     */
    public function verifyResetOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.regex' => 'The verification code must be exactly 6 digits.',
            'otp.required' => 'Please enter the 6-digit verification code.',
        ]);

        $email = session('reset_password_email') ?? session('blocked_email');
        if (! $email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please start over.']);
        }

        try {
            $this->otpService->verify($email, $request->otp, 'password_reset');
        } catch (ValidationException $e) {
            $dbUser = User::where('email', strtolower($email))->first();
            if (($dbUser && ! $dbUser->is_active) || session('account_blocked')) {
                Auth::logout();
            }
            throw $e;
        }

        // Mark OTP as verified for reset flow and generate secure reset token
        $token = Str::random(64);
        session()->put('reset_verified_token', $token);
        session()->forget(['dev_otp_code', 'account_blocked', 'blocked_email']);

        return redirect()->route('password.reset.form', ['token' => $token]);
    }

    /**
     * Show the New Password form after OTP verification.
     */
    public function showResetPasswordForm(Request $request, string $token): View|RedirectResponse
    {
        $sessionToken = session('reset_verified_token');
        $email = session('reset_password_email');

        if (! $sessionToken || ! hash_equals($sessionToken, $token) || ! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Invalid or expired password reset session. Please request a new code.']);
        }

        return view('auth.reset-password-otp', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Store the newly updated password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $sessionToken = session('reset_verified_token');
        $sessionEmail = session('reset_password_email');

        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        if (! $sessionToken || ! hash_equals($sessionToken, $request->token) || strtolower($sessionEmail) !== strtolower($request->email)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Security check failed. Please restart the password reset process.']);
        }

        $user = User::where('email', strtolower($request->email))->first();
        if (! $user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Clear session reset markers
        session()->forget(['reset_verified_token', 'reset_password_email']);

        // Log the user in securely
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Your password has been securely updated!');
    }
}
