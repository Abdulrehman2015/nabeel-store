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
        $validStatuses = ['confirmed', 'processing', 'out_for_delivery', 'delivered'];
        $todayRevenue = Order::whereDate('created_at', $today)
            ->whereIn('order_status', $validStatuses)
            ->sum('grand_total');

        // Check if purchase_price exists in DB schema (handles legacy / unmigrated hosting gracefully)
        $hasPurchasePrice = \Illuminate\Support\Facades\Schema::hasColumn('products', 'purchase_price');
        $itemCostSql = $hasPurchasePrice ? 'COALESCE(products.purchase_price, products.price * 0.70, 0)' : 'COALESCE(products.price * 0.70, 0)';
        $inventoryCostSql = $hasPurchasePrice ? 'COALESCE(purchase_price, price * 0.70, 0)' : 'COALESCE(price * 0.70, 0)';

        // Sales Profit Engine: Sale Product Price - Purchase Product Price = Profit
        $salesProfitQuery = \DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('orders.order_status', $validStatuses);

        // All-time confirmed sales breakdown
        $allTimeSales = (clone $salesProfitQuery)->selectRaw("
            COALESCE(SUM(order_items.line_total), 0) as total_sale,
            COALESCE(SUM(order_items.quantity * {$itemCostSql}), 0) as total_cost
        ")->first();

        $allTimeSaleRevenue = (float) ($allTimeSales->total_sale ?? 0);
        $allTimePurchaseCost = (float) ($allTimeSales->total_cost ?? 0);
        $totalProfit = round($allTimeSaleRevenue - $allTimePurchaseCost, 2);
        $totalProfitMargin = $allTimeSaleRevenue > 0 ? round(($totalProfit / $allTimeSaleRevenue) * 100, 1) : 0;

        // Today's realized sales profit
        $todaySales = (clone $salesProfitQuery)
            ->whereDate('orders.created_at', $today)
            ->selectRaw("
                COALESCE(SUM(order_items.line_total), 0) as total_sale,
                COALESCE(SUM(order_items.quantity * {$itemCostSql}), 0) as total_cost
            ")->first();

        $todaySaleRevenue = (float) ($todaySales->total_sale ?? 0);
        $todayPurchaseCost = (float) ($todaySales->total_cost ?? 0);
        $todayProfit = round($todaySaleRevenue - $todayPurchaseCost, 2);
        $todayProfitMargin = $todaySaleRevenue > 0 ? round(($todayProfit / $todaySaleRevenue) * 100, 1) : 0;

        // Catalog inventory stock potential profit
        $catalogStats = Product::where('is_active', true)->selectRaw("
            COALESCE(SUM(price * stock_quantity), 0) as total_retail_value,
            COALESCE(SUM({$inventoryCostSql} * stock_quantity), 0) as total_inventory_cost,
            COALESCE(SUM(price), 0) as total_catalog_sale,
            COALESCE(SUM({$inventoryCostSql}), 0) as total_catalog_purchase
        ")->first();

        $totalInventoryValue = (float) ($catalogStats->total_retail_value ?? 0);
        $totalInventoryCost = (float) ($catalogStats->total_inventory_cost ?? 0);
        $potentialInventoryProfit = round($totalInventoryValue - $totalInventoryCost, 2);
        $inventoryProfitMargin = $totalInventoryValue > 0 ? round(($potentialInventoryProfit / $totalInventoryValue) * 100, 1) : 0;

        $pendingPaymentsCount = Order::where('order_status', 'pending_payment_verification')->count();
        $pendingOrdersCount = Order::whereIn('order_status', ['confirmed', 'processing'])->count();

        // Low stock products alert (<= individual low_stock_threshold, default 10)
        $hasThreshold = \Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold');
        $rawCondition = $hasThreshold ? 'stock_quantity <= COALESCE(low_stock_threshold, 10)' : 'stock_quantity <= 10';

        $lowStockProducts = Product::where('is_active', true)
            ->whereRaw($rawCondition)
            ->orderBy('stock_quantity', 'asc')
            ->take(8)
            ->get();

        $lowStockCount = Product::where('is_active', true)
            ->whereRaw($rawCondition)
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
            'allTimeSaleRevenue',
            'allTimePurchaseCost',
            'totalProfit',
            'totalProfitMargin',
            'todaySaleRevenue',
            'todayPurchaseCost',
            'todayProfit',
            'todayProfitMargin',
            'totalInventoryValue',
            'totalInventoryCost',
            'potentialInventoryProfit',
            'inventoryProfitMargin',
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
     * Excludes in-store walk-in POS counter orders so bell sound & popups only trigger for online customer orders.
     */
    public function pollNotifications(Request $request): JsonResponse
    {
        $lastOrderId = (int) $request->query('last_order_id', 0);

        // Only count online orders awaiting payment verification (excludes walk-in POS sales)
        $pendingPayments = Order::where('order_status', 'pending_payment_verification')
            ->where('delivery_zone', '!=', 'pos_counter')
            ->where('order_number', 'NOT LIKE', 'POS-%')
            ->count();

        // Only track latest online storefront orders
        $latestOnlineOrder = Order::where('delivery_zone', '!=', 'pos_counter')
            ->where('order_number', 'NOT LIKE', 'POS-%')
            ->latest('id')
            ->first();
        $latestOrderId = $latestOnlineOrder ? (int) $latestOnlineOrder->id : 0;

        $newOrders = [];
        if ($lastOrderId > 0 && $latestOrderId > $lastOrderId) {
            $newOrders = Order::with('items')
                ->where('id', '>', $lastOrderId)
                ->where('delivery_zone', '!=', 'pos_counter')
                ->where('order_number', 'NOT LIKE', 'POS-%')
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

        // Track low stock products based on individual low_stock_threshold
        $hasThreshold = \Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold');
        $rawCondition = $hasThreshold ? 'stock_quantity <= COALESCE(low_stock_threshold, 10)' : 'stock_quantity <= 10';

        $lowStockProducts = Product::where('is_active', true)
            ->whereRaw($rawCondition)
            ->orderBy('stock_quantity', 'asc')
            ->take(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'stock_quantity' => (int) $product->stock_quantity,
                    'low_stock_threshold' => (int) ($product->low_stock_threshold ?? 10),
                    'url' => route('admin.products.edit', $product),
                ];
            });
        $lowStockCount = Product::where('is_active', true)
            ->whereRaw($rawCondition)
            ->count();

        return response()->json([
            'pending_payments' => $pendingPayments,
            'low_stock_count' => $lowStockCount,
            'low_stock_items' => $lowStockProducts,
            'total_alerts' => $pendingPayments + $lowStockCount,
            'latest_order_id' => $latestOrderId,
            'latest_order_number' => $latestOnlineOrder?->order_number,
            'latest_order_time' => $latestOnlineOrder?->created_at?->diffForHumans(),
            'has_new_orders' => count($newOrders) > 0,
            'new_orders' => $newOrders,
        ]);
    }
}

