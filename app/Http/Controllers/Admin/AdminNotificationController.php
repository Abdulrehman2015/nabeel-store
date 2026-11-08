<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    /**
     * Display the Daily Notifications & Alert Center.
     */
    public function index(Request $request): View
    {
        // 1. Automatically sync today's events and enforce 30-day retention purge
        AdminNotification::syncDailyAlerts();

        // 2. Base Query
        $query = AdminNotification::query()->latest('created_at');

        // 3. Date Filters (Daily-focused)
        $dateFilter = $request->query('date', 'today');
        $customDate = $request->query('custom_date');

        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateFilter === 'last_7_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
        } elseif ($dateFilter === 'custom' && !empty($customDate)) {
            $query->whereDate('created_at', $customDate);
        }
        // 'all' includes all retained records within the 30-day window

        // 4. Type Filter
        if ($request->filled('type') && $request->query('type') !== 'all') {
            $query->where('type', $request->query('type'));
        }

        // 5. Read / Unread Status Filter
        if ($request->filled('status')) {
            if ($request->query('status') === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->query('status') === 'read') {
                $query->where('is_read', true);
            }
        }

        // 6. Search keyword
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $notifications = $query->paginate(25)->withQueryString();

        // 7. Calculate Real-Time Stats
        $todayCount = AdminNotification::whereDate('created_at', Carbon::today())->count();
        $unreadCount = AdminNotification::where('is_read', false)->count();
        $ordersCount = AdminNotification::whereDate('created_at', Carbon::today())
            ->whereIn('type', ['order', 'payment'])
            ->count();
        $lowStockCount = AdminNotification::whereDate('created_at', Carbon::today())
            ->where('type', 'inventory')
            ->count();

        return view('admin.notifications.index', compact(
            'notifications',
            'todayCount',
            'unreadCount',
            'ordersCount',
            'lowStockCount',
            'dateFilter',
            'customDate'
        ));
    }

    /**
     * Mark a single notification as read / unread toggle.
     */
    public function markAsRead(Request $request, AdminNotification $notification)
    {
        if ($notification->is_read) {
            $notification->markAsUnread();
            $message = 'Notification marked as unread.';
        } else {
            $notification->markAsRead();
            $message = 'Notification marked as read.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_read' => $notification->is_read,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $scope = $request->input('scope', 'all');

        if ($scope === 'today') {
            AdminNotification::whereDate('created_at', Carbon::today())
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
            $msg = "All of today's notifications have been marked as read.";
        } else {
            AdminNotification::where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
            $msg = 'All notifications have been marked as read.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Delete an individual notification.
     */
    public function destroy(Request $request, AdminNotification $notification)
    {
        $notification->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification removed.',
            ]);
        }

        return redirect()->back()->with('success', 'Notification removed.');
    }

    /**
     * Manually trigger 30-day retention cleanup.
     */
    public function purgeOld(): RedirectResponse
    {
        $deleted = AdminNotification::purgeExpired(30);

        return redirect()->back()->with(
            'success',
            $deleted > 0
                ? "Cleaned up {$deleted} expired notification(s) older than 30 days."
                : 'All notifications are already within the 30-day retention window.'
        );
    }

    /**
     * Clear all current notifications.
     */
    public function clearAll(Request $request): RedirectResponse
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can clear notification history.');
        }

        AdminNotification::truncate();

        return redirect()->back()->with('success', 'All notifications cleared.');
    }
}
