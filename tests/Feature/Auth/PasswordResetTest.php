<?php

namespace Tests\Feature\Auth;

use App\Models\OtpCode;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Reset Your Password');
    }

    public function test_reset_password_otp_can_be_requested(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect(route('password.reset.otp.notice'));
        $this->assertDatabaseHas('otp_codes', [
            'identifier' => $user->email,
            'type' => 'password_reset',
        ]);
    }

    public function test_reset_password_otp_can_be_verified_and_password_updated(): void
    {
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        $code = session('dev_otp_code');
        $this->assertNotNull($code);

        // Verify OTP
        $response = $this->post(route('password.reset.otp.verify'), [
            'otp' => $code,
        ]);

        $token = session('reset_verified_token');
        $this->assertNotNull($token);

        // Reset password
        $response = $this->post(route('password.update.otp'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewSecu!Pass123',
            'password_confirmation' => 'NewSecu!Pass123',
        ]);

        $response->assertRedirect(route('home'));
        $user->refresh();
        $this->assertTrue(Hash::check('NewSecu!Pass123', $user->password));
    }
}
