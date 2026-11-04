<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'We could not find an account registered with this email address.',
            ]);
        }

        if (! $user->is_active) {
            $contact = $this->otpService->getAdminContact();
            throw ValidationException::withMessages([
                'email' => "This account has been blocked. Please contact the administrator at {$contact['email']}" . ($contact['phone'] ? " or {$contact['phone']}" : "") . ".",
            ]);
        }

        try {
            $this->otpService->generate($email, 'password_reset', $user);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        session(['reset_password_email' => $email]);

        return redirect()->route('password.reset.otp.notice')
            ->with('status', 'A 6-digit password reset code has been generated. Please enter it below.');
    }
}
