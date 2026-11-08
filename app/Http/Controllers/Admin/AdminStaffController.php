<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminStaffController extends Controller
{
    /**
     * Display a listing of staff & cashier users.
     */
    public function index(): View
    {
        $today = today();

        $staffMembers = User::whereIn('role', ['admin', 'staff', 'cashier'])
            ->withCount([
                'cashierOrders as today_orders_count' => function ($q) use ($today) {
                    $q->whereDate('created_at', $today)->where('order_status', 'delivered');
                },
                'cashierOrders as all_time_orders_count' => function ($q) {
                    $q->where('order_status', 'delivered');
                },
            ])
            ->withSum([
                'cashierOrders as today_sales_sum' => function ($q) use ($today) {
                    $q->whereDate('created_at', $today)->where('order_status', 'delivered');
                },
            ], 'grand_total')
            ->withSum([
                'cashierOrders as all_time_sales_sum' => function ($q) {
                    $q->where('order_status', 'delivered');
                },
            ], 'grand_total')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Show form for creating a new cashier / staff member.
     */
    public function create(): View
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created cashier / staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'role' => ['required', Rule::in(['admin', 'cashier', 'staff'])],
            'password' => 'required|string|min:6|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        ActivityLog::log('staff.created', $user, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_by' => auth()->user()->name,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', "Staff member {$user->name} created successfully with {$user->role} permissions.");
    }

    /**
     * Show form for editing an existing staff member.
     */
    public function edit(User $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($staff->id)],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($staff->id)],
            'role' => ['required', Rule::in(['admin', 'cashier', 'staff'])],
            'password' => 'nullable|string|min:6|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        // Guard against locking out current logged in administrator
        if ($staff->id === auth()->id()) {
            if ($validated['role'] !== 'admin') {
                return back()->with('error', 'You cannot revoke your own administrator role.');
            }
            if (!$request->boolean('is_active', true)) {
                return back()->with('error', 'You cannot deactivate your own account.');
            }
        }

        $data = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $staff->update($data);

        ActivityLog::log('staff.updated', $staff, [
            'name' => $staff->name,
            'role' => $staff->role,
            'updated_by' => auth()->user()->name,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', "Staff account for {$staff->name} updated successfully.");
    }

    /**
     * Toggle active/inactive status for a staff member.
     */
    public function toggleStatus(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $staff->update(['is_active' => !$staff->is_active]);

        $statusText = $staff->is_active ? 'activated' : 'deactivated';

        ActivityLog::log('staff.status_toggled', $staff, [
            'name' => $staff->name,
            'is_active' => $staff->is_active,
            'by' => auth()->user()->name,
        ]);

        return back()->with('success', "Staff account for {$staff->name} has been {$statusText}.");
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own administrator account.');
        }

        if ($staff->id === 1) {
            return back()->with('error', 'The primary system administrator account cannot be deleted.');
        }

        $name = $staff->name;
        ActivityLog::log('staff.deleted', null, [
            'name' => $name,
            'email' => $staff->email,
            'by' => auth()->user()->name,
        ]);

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', "Staff account for {$name} deleted successfully.");
    }
}

