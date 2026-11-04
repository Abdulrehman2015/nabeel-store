<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile edit view.
     */
    public function edit(): View
    {
        $user = auth()->user();

        // Recent security activity logs for current admin
        $recentLogs = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        return view('admin.profile.edit', compact('user', 'recentLogs'));
    }

    /**
     * Update the admin's name, email, and phone.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $oldEmail = $user->email;
        $user->fill($validated);

        // If email was changed, update and log
        $emailChanged = $user->isDirty('email');
        $user->save();

        ActivityLog::log('admin_profile_updated', $user, [
            'name' => $user->name,
            'email' => $user->email,
            'email_changed' => $emailChanged,
            'old_email' => $emailChanged ? $oldEmail : null,
        ]);

        $message = $emailChanged 
            ? 'Profile updated successfully! Your administrative login email is now ' . $user->email . '.' 
            : 'Admin profile updated successfully!';

        return redirect()->route('admin.profile.edit')->with('success', $message);
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The provided current password does not match our records.',
            'password.confirmed' => 'The new password confirmation does not match.',
            'password.min' => 'The new password must be at least 8 characters long.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::log('admin_password_changed', $user, [
            'message' => 'Admin password was updated successfully',
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Security password changed successfully! Use your new password on your next login.');
    }
}
