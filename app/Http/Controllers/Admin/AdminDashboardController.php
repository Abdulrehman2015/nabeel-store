<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $sevenDayRevenueTotal = array_sum($chartRevenue);
        $sevenDayOrdersTotal = array_sum($chartOrders);

        // Wholesale fulfillment pipeline counts
        $pipelineCounts = [
            'pending_verification' => Order::where('order_status', 'pending_payment_verification')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'out_for_delivery' => Order::where('order_status', 'out_for_delivery')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
        ];

        $totalActiveProducts = Product::where('is_active', true)->count();
        $totalOrdersCount = Order::count();

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
            'chartOrders',
            'sevenDayRevenueTotal',
            'sevenDayOrdersTotal',
            'pipelineCounts',
            'totalActiveProducts',
            'totalOrdersCount'
        ));
    }

    /**
     * Poll endpoint for in-app notification bell and instantaneous order alert (every 5-6 seconds).
     */
    public function pollNotifications(Request $request): JsonResponse
    {
        $lastOrderId = (int) $request->query('last_order_id', 0);
        $pendingPayments = Order::where('order_status', 'pending_payment_verification')->count();
        $latestOrder = Order::latest('id')->first();
        $latestOrderId = $latestOrder ? (int) $latestOrder->id : 0;

        $newOrders = [];
        if ($lastOrderId > 0 && $latestOrderId > $lastOrderId) {
            $newOrders = Order::with('items')
                ->where('id', '>', $lastOrderId)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get()
                ->map(function ($order) {
                    $itemsCount = $order->items ? (int) $order->items->sum('quantity') : 1;
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer_name ?: 'Online Shopper',
                        'customer_phone' => $order->customer_phone ?: '',
                        'grand_total' => number_format((float) $order->grand_total, 2),
                        'items_count' => $itemsCount ?: 1,
                        'payment_method' => $order->payment_method,
                        'order_status' => $order->order_status,
                        'time_ago' => $order->created_at ? $order->created_at->diffForHumans() : 'just now',
                        'url' => route('admin.orders.show', $order),
                    ];
                });
        }

        return response()->json([
            'pending_payments' => $pendingPayments,
            'latest_order_id' => $latestOrderId,
            'latest_order_number' => $latestOrder?->order_number,
            'latest_order_time' => $latestOrder?->created_at?->diffForHumans(),
            'has_new_orders' => count($newOrders) > 0,
            'new_orders' => $newOrders,
        ]);
    }
}

