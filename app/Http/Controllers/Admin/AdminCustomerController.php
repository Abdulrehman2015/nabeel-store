<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountUnlockedMail;
use App\Models\ActivityLog;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->latest();

        $status = $request->query('status');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'suspended') {
            $query->where('is_active', false);
        } elseif ($status === 'with_orders') {
            $query->has('orders');
        }

        if ($request->filled('search')) {
            $s = trim($request->query('search'));
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                    ->orWhere('email', 'LIKE', "%{$s}%")
                    ->orWhere('phone', 'LIKE', "%{$s}%");
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::where('role', 'customer')->count(),
            'active' => User::where('role', 'customer')->where('is_active', true)->count(),
            'suspended' => User::where('role', 'customer')->where('is_active', false)->count(),
            'with_orders' => User::where('role', 'customer')->has('orders')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $user)
    {
        if ($user->role !== 'customer') {
            abort(404);
        }

        $user->load(['orders.items', 'addresses']);

        return view('admin.customers.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        $willBeActive = ! $user->is_active;

        if ($willBeActive) {
            // UNLOCKING / ACTIVATING CUSTOMER
            // 1. Generate new secure temporary password
            $temporaryPassword = 'NJ#' . random_int(100000, 999999);

            // 2. Update user credentials and unlock
            $user->password = Hash::make($temporaryPassword);
            $user->is_active = true;
            $user->save();

            // 3. Clear failed OTP cycles and invalid codes
            Cache::forget("otp_failed_cycles:" . strtolower($user->email));
            OtpCode::where('identifier', strtolower($user->email))->delete();

            ActivityLog::log('customer.account_unlocked', $user, [
                'admin_id' => auth()->id(),
                'email' => $user->email,
            ]);

            // 4. Send email immediately to the customer with new password
            try {
                Mail::to($user->email)->send(new AccountUnlockedMail($user, $temporaryPassword));
                $emailStatus = "An email with the new login password ({$temporaryPassword}) has been sent immediately to {$user->email}.";
            } catch (\Throwable $e) {
                Log::error("Failed to send account unlocked email to {$user->email}: " . $e->getMessage());
                $emailStatus = "Account is unlocked, but email sending failed. New password: {$temporaryPassword}";
            }

            return redirect()->back()->with('success', "Customer account '{$user->name}' has been unlocked. {$emailStatus}");
        }

        // SUSPENDING / DEACTIVATING CUSTOMER
        $user->is_active = false;
        $user->save();

        ActivityLog::log('customer.account_suspended', $user, [
            'admin_id' => auth()->id(),
            'email' => $user->email,
        ]);

        return redirect()->back()->with('success', "Customer account '{$user->name}' has been suspended.");
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'customer') {
            return redirect()->back()->with('error', 'Cannot delete administrative or staff accounts.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $customerName = $user->name;
        $customerEmail = $user->email;

        ActivityLog::log('customer.deleted', null, [
            'deleted_user_id' => $user->id,
            'name' => $customerName,
            'email' => $customerEmail,
            'deleted_by' => auth()->id(),
        ]);

        $user->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', "Customer '{$customerName}' has been permanently deleted.");
    }

    public function deleteAll(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->where('id', '!=', auth()->id())
            ->get();

        $count = $customers->count();

        foreach ($customers as $customer) {
            $customer->delete();
        }

        ActivityLog::log('customers.deleted_all', null, [
            'count' => $count,
            'deleted_by' => auth()->id(),
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', "All {$count} registered customer accounts have been permanently deleted.");
    }
}

