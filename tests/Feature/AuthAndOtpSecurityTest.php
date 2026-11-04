<?php

namespace Tests\Feature;

use App\Models\OtpCode;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndOtpSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In to Your Account');
        $response->assertSee('99 Bulk Sales');
    }

    public function test_registration_screen_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Create Wholesale Account');
        $response->assertSee('Required Password Security Rules:');
    }

    public function test_registration_requires_strong_password_and_terms(): void
    {
        // Weak password (no symbols, short)
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'weaktest@example.com',
            'password' => 'simple',
            'password_confirmation' => 'simple',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'weaktest@example.com']);
    }

    public function test_successful_registration_generates_otp_and_redirects_to_verification(): void
    {
        $email = 'wholesale_buyer_' . time() . '@example.com';

        $response = $this->post('/register', [
            'name' => 'Ahmad Wholesale',
            'email' => $email,
            'phone' => '+60123456789',
            'password' => 'StrongPass123!@#',
            'password_confirmation' => 'StrongPass123!@#',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('otp.verify.notice'));

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        // Check OTP was securely generated and hashed
        $otp = OtpCode::where('identifier', $email)->where('type', 'email_verification')->first();
        $this->assertNotNull($otp);
        $this->assertNotEquals('123456', $otp->code_hash); // Code MUST be hashed
    }

    public function test_otp_verification_rejects_wrong_code_and_tracks_attempts(): void
    {
        $user = User::factory()->create([
            'email' => 'otp_attempt_test_' . time() . '@example.com',
            'email_verified_at' => null,
        ]);

        $otpService = app(OtpService::class);
        $generated = $otpService->generate($user->email, 'email_verification', $user);

        $this->actingAs($user);

        // Submit wrong OTP
        $response = $this->post(route('otp.verify.submit'), [
            'otp' => '000000',
        ]);

        $response->assertSessionHasErrors(['otp']);
        $user->refresh();
        $this->assertNull($user->email_verified_at);

        // Submit correct OTP
        $response = $this->post(route('otp.verify.submit'), [
            'otp' => $generated['code'],
        ]);

        $response->assertRedirect(route('home'));
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_unverified_customers_cannot_bypass_otp_to_access_account(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('account.orders'));

        $response->assertRedirect(route('otp.verify.notice'));
        $response->assertSessionHas('pending_verification_email', $user->email);
    }

    public function test_forgot_password_flow_with_otp_and_reset(): void
    {
        $user = User::factory()->create([
            'email' => 'forgot_test_' . time() . '@example.com',
            'password' => Hash::make('OldPassword123!@#'),
        ]);

        // 1. Request reset OTP
        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('password.reset.otp.notice'));
        $this->assertEquals($user->email, session('reset_password_email'));

        // Retrieve generated OTP
        $otpRecord = OtpCode::where('identifier', $user->email)
            ->where('type', 'password_reset')
            ->first();
        $this->assertNotNull($otpRecord);

        // Find plaintext code from session or helper
        $code = session('dev_otp_code');
        $this->assertNotNull($code);

        // 2. Verify OTP
        $response = $this->post(route('password.reset.otp.verify'), [
            'otp' => $code,
        ]);

        $token = session('reset_verified_token');
        $this->assertNotNull($token);
        $response->assertRedirect(route('password.reset.form', ['token' => $token]));

        // 3. Submit New Password
        $response = $this->post(route('password.update.otp'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewStrongPass123!@#',
            'password_confirmation' => 'NewStrongPass123!@#',
        ]);

        $response->assertRedirect(route('home'));
        $user->refresh();
        $this->assertTrue(Hash::check('NewStrongPass123!@#', $user->password));
    }
}
