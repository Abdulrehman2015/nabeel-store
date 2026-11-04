<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'You must accept the Terms and Privacy Policy to create an account.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character symbol.',
        ]);

        $user = User::create([
            'name' => strip_tags(trim($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'phone' => isset($validated['phone']) ? trim($validated['phone']) : null,
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => null,
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        // Generate 6-digit OTP verification code
        $this->otpService->generate($user->email, 'email_verification', $user);

        // Store pending email in session
        session(['pending_verification_email' => $user->email]);

        // Log the user in so their session is established
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('otp.verify.notice')
            ->with('status', 'Account created! Enter the 6-digit verification code to complete activation.');
    }
}
