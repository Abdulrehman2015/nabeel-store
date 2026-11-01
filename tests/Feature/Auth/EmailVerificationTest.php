<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-otp');

        $response->assertStatus(200);
        $response->assertSee('Verify Wholesale Account');
    }

    public function test_email_can_be_verified_with_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otpService = app(OtpService::class);
        $generated = $otpService->generate($user->email, 'email_verification', $user);

        $response = $this->actingAs($user)->post('/verify-otp', [
            'otp' => $generated['code'],
        ]);

        $response->assertRedirect(route('home'));
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_email_is_not_verified_with_invalid_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otpService = app(OtpService::class);
        $otpService->generate($user->email, 'email_verification', $user);

        $response = $this->actingAs($user)->post('/verify-otp', [
            'otp' => '999999',
        ]);

        $response->assertSessionHasErrors(['otp']);
        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }
}
