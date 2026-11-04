<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OtpService
{
    /**
     * Expiration time in minutes.
     */
    public const EXPIRATION_MINUTES = 10;

    /**
     * Minimum cooldown between OTP requests in seconds.
     */
    public const RESEND_COOLDOWN_SECONDS = 60;

    /**
     * Retrieve dynamic administrator contact details from settings.
     */
    public function getAdminContact(): array
    {
        $email = setting('admin_email');
        if (empty($email)) {
            $email = setting('store_email', 'admin@99bulksales.my');
        }

        $phone = setting('admin_phone');
        if (empty($phone)) {
            $phone = setting('store_phone', '+60 12-889 9900');
        }

        return [
            'email' => $email,
            'phone' => $phone,
        ];
    }

    /**
     * Check if the user account matching the identifier is blocked.
     */
    public function isUserBlocked(string $identifier): bool
    {
        $user = User::where('email', strtolower(trim($identifier)))->first();
        return $user && ! $user->is_active;
    }

    /**
     * Generate and store an OTP for the given identifier and type.
     *
     * @return array{code: string, expires_at: \Carbon\CarbonInterface}
     */
    public function generate(string $identifier, string $type = 'email_verification', ?User $user = null): array
    {
        $identifier = strtolower(trim($identifier));

        // Block generation if the account has been suspended/blocked
        if ($this->isUserBlocked($identifier)) {
            $contact = $this->getAdminContact();
            throw ValidationException::withMessages([
                'otp' => "This account has been blocked. Please contact the administrator (Email: {$contact['email']} | Phone: {$contact['phone']}).",
            ]);
        }

        // Check cooldown to prevent flooding
        $latest = OtpCode::where('identifier', $identifier)
            ->where('type', $type)
            ->latest()
            ->first();

        if ($latest && $latest->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            $remaining = self::RESEND_COOLDOWN_SECONDS - $latest->created_at->diffInSeconds(now());
            throw ValidationException::withMessages([
                'otp' => "Please wait {$remaining} seconds before requesting a new code.",
            ]);
        }

        // Invalidate older unverified codes for this identifier and type
        OtpCode::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->delete();

        $code = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(self::EXPIRATION_MINUTES);

        $record = OtpCode::create([
            'user_id' => $user?->id,
            'identifier' => $identifier,
            'code_hash' => Hash::make($code),
            'type' => $type,
            'attempts' => 0,
            'max_attempts' => 5,
            'expires_at' => $expiresAt,
        ]);

        // Log code securely for development/audit
        Log::info("OTP generated for [{$identifier}], type [{$type}], expires at [{$expiresAt}]");

        // Keep plaintext OTPs out of sessions outside local/test environments.
        if (app()->environment(['local', 'testing'])) {
            try {
                session()->flash('dev_otp_code', $code);
            } catch (\Throwable $e) {
                Log::warning("Could not set session flash dev_otp_code: {$e->getMessage()}");
            }
        }

        return [
            'code' => $code,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Verify an OTP code.
     * Enforces:
     * 1) 5 failed attempts on an OTP -> "Your OTP is failed, please resend the OTP."
     * 2) 2nd time failing 5 attempts -> Block user account & instruct to contact admin (with dynamic email and phone).
     *
     * @throws ValidationException
     */
    public function verify(string $identifier, string $code, string $type = 'email_verification'): bool
    {
        $identifier = strtolower(trim($identifier));
        $code = trim($code);

        // Atomic lock per identifier prevents race-condition brute force / concurrent burst attacks
        return Cache::lock("otp_verify_lock:{$identifier}", 5)->block(3, function () use ($identifier, $code, $type) {
            // Security Check: Is the account already blocked?
            if ($this->isUserBlocked($identifier)) {
                $contact = $this->getAdminContact();
                throw ValidationException::withMessages([
                    'otp' => "Your account has been blocked. Please contact the administrator at {$contact['email']} or {$contact['phone']}.",
                ]);
            }

            $record = OtpCode::where('identifier', $identifier)
                ->where('type', $type)
                ->whereNull('verified_at')
                ->latest()
                ->first();

            if (! $record || $record->isExpired()) {
                throw ValidationException::withMessages([
                    'otp' => 'Your OTP is failed, please resend the OTP.',
                ]);
            }

            if ($record->isMaxAttemptsReached()) {
                $record->delete();
                throw ValidationException::withMessages([
                    'otp' => 'Your OTP is failed, please resend the OTP.',
                ]);
            }

            // Increment attempts count
            $record->increment('attempts');

            if (! Hash::check($code, $record->code_hash)) {
                $currentAttempts = $record->attempts;
                $maxAttempts = $record->max_attempts; // 5

                if ($currentAttempts >= $maxAttempts) {
                    // Delete this failed OTP code so it can no longer be used
                    $record->delete();

                    // Track how many cycles of 5 failed attempts this user has completed
                    $cycleKey = "otp_failed_cycles:" . $identifier;
                    $failedCycles = (int) Cache::get($cycleKey, 0) + 1;
                    Cache::put($cycleKey, $failedCycles, now()->addDays(7));

                    if ($failedCycles >= 2) {
                        // 2nd time user failed 5 attempts: Block the account permanently!
                        $user = User::where('email', $identifier)->first();
                        if ($user) {
                            $user->is_active = false;
                            $user->save();

                            ActivityLog::log('customer.account_blocked', $user, [
                                'reason' => 'Failed OTP verification 5 attempts twice',
                                'identifier' => $identifier,
                            ]);
                        }

                        // Invalidate any active OTP records
                        OtpCode::where('identifier', $identifier)->delete();

                        $contact = $this->getAdminContact();

                        session()->put('account_blocked', true);
                        session()->put('blocked_email', $identifier);
                        session()->put('blocked_admin_email', $contact['email']);
                        session()->put('blocked_admin_phone', $contact['phone']);

                        throw ValidationException::withMessages([
                            'otp' => "Your account has been blocked due to multiple failed verification attempts. Please contact the admin. Email: {$contact['email']} | Phone: {$contact['phone']}",
                        ]);
                    }

                    // 1st time user failed 5 attempts
                    session()->flash('otp_failed_resend_required', true);

                    throw ValidationException::withMessages([
                        'otp' => 'Your OTP is failed, please resend the OTP.',
                    ]);
                }

                $remaining = $maxAttempts - $currentAttempts;
                throw ValidationException::withMessages([
                    'otp' => "Incorrect verification code. {$remaining} attempts remaining.",
                ]);
            }

            // Successfully verified
            $record->update(['verified_at' => now()]);

            // Clear failed cycles counter and blocked markers
            Cache::forget("otp_failed_cycles:" . $identifier);
            session()->forget([
                'account_blocked',
                'blocked_email',
                'blocked_admin_email',
                'blocked_admin_phone',
                'otp_failed_resend_required',
            ]);

            return true;
        });
    }
}
