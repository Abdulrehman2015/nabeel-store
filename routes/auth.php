<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:10,1');

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:10,1');

    // Forgot Password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('password.email');

    // Password Reset via OTP
    Route::get('verify-reset-otp', [OtpVerificationController::class, 'showResetOtpNotice'])
        ->name('password.reset.otp.notice');
    Route::post('verify-reset-otp', [OtpVerificationController::class, 'verifyResetOtp'])
        ->middleware('throttle:10,1')
        ->name('password.reset.otp.verify');

    Route::get('reset-password/{token}', [OtpVerificationController::class, 'showResetPasswordForm'])
        ->name('password.reset.form');
    Route::post('reset-password', [OtpVerificationController::class, 'updatePassword'])
        ->middleware('throttle:10,1')
        ->name('password.update.otp');
});

// OTP Verification (accessible for logged-in or pending verification)
Route::get('verify-otp', [OtpVerificationController::class, 'showNotice'])
    ->name('otp.verify.notice');
Route::get('verify-email', [OtpVerificationController::class, 'showNotice'])
    ->name('verification.notice');

Route::post('verify-otp', [OtpVerificationController::class, 'verify'])
    ->middleware('throttle:10,1')
    ->name('otp.verify.submit');

Route::post('resend-otp', [OtpVerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('otp.resend');
Route::post('email/verification-notification', [OtpVerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

Route::middleware('auth')->group(function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
