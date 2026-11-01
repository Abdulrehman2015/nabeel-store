<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Key Metrics
        $todayOrdersCount = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)
            ->whereIn('order_status', ['confirmed', 'processing', 'out_for_delivery', 'delivered'])
            ->sum('grand_total');

        $pendingPaymentsCount = Order::where('order_status', 'pending_payment_verification')->count();
        $pendingOrdersCount = Order::whereIn('order_status', ['confirmed', 'processing'])->count();

        // Low stock products alert (<= 20 cartons)
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 20)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        $lowStockCount = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 20)
            ->count();

        // Recent Orders
        $recentOrders = Order::with('items')
            ->latest()
            ->take(8)
            ->get();

        // 7-day Sales Chart Data
        $chartDates = [];
        $chartRevenue = [];
        $chartOrders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('M d');
            $chartDates[] = $dateStr;

            $rev = Order::whereDate('created_at', $date)
                ->whereIn('order_status', ['confirmed', 'processing', 'out_for_delivery', 'delivered'])
                ->sum('grand_total');

            $cnt = Order::whereDate('created_at', $date)->count();

            $chartRevenue[] = round($rev, 2);
            $chartOrders[] = $cnt;
        }

        return view('admin.dashboard', compact(
            'todayOrdersCount',
            'todayRevenue',
            'pendingPaymentsCount',
            'pendingOrdersCount',
            'lowStockProducts',
            'lowStockCount',
            'recentOrders',
            'chartDates',
            'chartRevenue',
            'chartOrders'
        ));
    }

    /**
     * Poll endpoint for in-app notification bell (every 30 seconds).
     */
    public function pollNotifications(): JsonResponse
    {
        $pendingPayments = Order::where('order_status', 'pending_payment_verification')->count();
        $latestOrder = Order::latest()->first();

        return response()->json([
            'pending_payments' => $pendingPayments,
            'latest_order_number' => $latestOrder?->order_number,
            'latest_order_time' => $latestOrder?->created_at->diffForHumans(),
        ]);
    }
}

