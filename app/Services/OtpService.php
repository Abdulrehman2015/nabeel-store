<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
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
     * Generate and store an OTP for the given identifier and type.
     *
     * @return array{code: string, expires_at: \Carbon\CarbonInterface}
     */
    public function generate(string $identifier, string $type = 'email_verification', ?User $user = null): array
    {
        $identifier = strtolower(trim($identifier));

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

        // Send notification email (logged if MAIL_MAILER=log)
        try {
            // Store plain code in session for local helper banner display if needed
            session()->flash('dev_otp_code', $code);
        } catch (\Throwable $e) {
            Log::warning("Could not set session flash dev_otp_code: {$e->getMessage()}");
        }

        return [
            'code' => $code,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Verify an OTP code.
     *
     * @throws ValidationException
     */
    public function verify(string $identifier, string $code, string $type = 'email_verification'): bool
    {
        $identifier = strtolower(trim($identifier));
        $code = trim($code);

        $record = OtpCode::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $record) {
            throw ValidationException::withMessages([
                'otp' => 'No active verification code found. Please request a new code.',
            ]);
        }

        if ($record->isExpired()) {
            throw ValidationException::withMessages([
                'otp' => 'This verification code has expired. Please request a new one.',
            ]);
        }

        if ($record->isMaxAttemptsReached()) {
            $record->delete();
            throw ValidationException::withMessages([
                'otp' => 'Too many failed attempts. For your security, this code was invalidated. Please request a new one.',
            ]);
        }

        // Increment attempts count
        $record->increment('attempts');

        if (! Hash::check($code, $record->code_hash)) {
            $remaining = $record->max_attempts - $record->attempts;
            throw ValidationException::withMessages([
                'otp' => "Incorrect verification code. {$remaining} attempts remaining.",
            ]);
        }

        // Successfully verified
        $record->update(['verified_at' => now()]);

        return true;
    }
}

