<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductExpense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncApiController extends Controller
{
    /**
     * Authenticate incoming sync API request via secret token.
     */
    protected function authenticateSyncToken(Request $request): ?JsonResponse
    {
        $token = $request->header('X-Sync-Token') ?: $request->query('sync_token');
        $validToken = config('sync.api_token', 'pos_sync_secret_key_nabeel_2026');

        if (!$token || !hash_equals($validToken, $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or missing sync security token.',
            ], 401);
        }

        return null;
    }

    /**
     * Health check / ping endpoint.
     */
    public function ping(Request $request): JsonResponse
    {
        if ($error = $this->authenticateSyncToken($request)) {
            return $error;
        }

        return response()->json([
            'status' => 'ok',
            'server_time' => now()->toISOString(),
            'app_name' => config('app.name', 'N&I Mini Mart'),
            'store_online' => true,
        ]);
    }

    /**
     * Ingest batch of offline orders, products, and expenses from offline workstation.
     */
    public function push(Request $request): JsonResponse
    {
        if ($error = $this->authenticateSyncToken($request)) {
            return $error;
        }

        $orders = $request->input('orders', []);
        $products = $request->input('products', []);
        $expenses = $request->input('expenses', []);

        $syncedUuids = [];
        $ordersProcessed = 0;
        $productsProcessed = 0;
        $expensesProcessed = 0;

        DB::beginTransaction();
        try {
            // 1. Process Offline Orders
            foreach ($orders as $orderData) {
                $syncUuid = $orderData['sync_uuid'] ?? null;
                $orderNumber = $orderData['order_number'] ?? null;
                $idempotencyToken = $orderData['idempotency_token'] ?? null;

                // Check if this order already exists on the live server (avoid duplicates)
                $existingOrder = null;
                if ($syncUuid) {
                    $existingOrder = Order::where('sync_uuid', $syncUuid)->first();
                }
                if (!$existingOrder && $idempotencyToken) {
                    $existingOrder = Order::where('idempotency_token', $idempotencyToken)->first();
                }
                if (!$existingOrder && $orderNumber) {
                    $existingOrder = Order::where('order_number', $orderNumber)->first();
                }

                if ($existingOrder) {
                    // Already exists: record as synced so local queue clears it
                    if ($syncUuid) {
                        $syncedUuids[] = $syncUuid;
                    }
                    continue;
                }

                // If order_number clashes with another live order, append an offline suffix
                $finalOrderNumber = $orderNumber ?: ('POS-' . date('Y') . '-' . strtoupper(Str::random(6)));
                if (Order::where('order_number', $finalOrderNumber)->exists()) {
                    $finalOrderNumber .= '-OFFLINE';
                }

                $newOrder = Order::create([
                    'order_number' => $finalOrderNumber,
                    'user_id' => $orderData['user_id'] ?? null,
                    'cashier_id' => $orderData['cashier_id'] ?? null,
                    'customer_name' => $orderData['customer_name'] ?? 'Walk-in Customer',
                    'customer_phone' => $orderData['customer_phone'] ?? '-',
                    'customer_email' => $orderData['customer_email'] ?? 'pos@nandjminimart.com',
                    'delivery_address' => $orderData['delivery_address'] ?? 'N&J Mini Mart Counter (Walk-in POS)',
                    'delivery_city' => $orderData['delivery_city'] ?? 'Puchong',
                    'floor' => 0,
                    'landmark' => 'In-Store Register (Offline Synced)',
                    'order_notes' => $orderData['order_notes'] ?? null,
                    'latitude' => 1.5206574,
                    'longitude' => 103.6616431,
                    'distance_km' => 0.00,
                    'delivery_zone' => $orderData['delivery_zone'] ?? 'pos_counter',
                    'subtotal' => (float) ($orderData['subtotal'] ?? 0),
                    'floor_charge' => 0.00,
                    'delivery_charge' => 0.00,
                    'grand_total' => (float) ($orderData['grand_total'] ?? 0),
                    'advance_percentage' => 100.00,
                    'advance_amount' => (float) ($orderData['grand_total'] ?? 0),
                    'balance_amount' => 0.00,
                    'payment_method' => $orderData['payment_method'] ?? 'cash',
                    'payment_reference' => $orderData['payment_reference'] ?? ('POS-OFFLINE-' . strtoupper(Str::random(6))),
                    'payment_status' => 'verified',
                    'payment_verified_at' => now(),
                    'order_status' => 'delivered',
                    'internal_notes' => ($orderData['internal_notes'] ?? '') . ' [Synced from Windows Offline Workstation at ' . now()->format('Y-m-d H:i:s') . ']',
                    'idempotency_token' => $idempotencyToken ?: Str::uuid()->toString(),
                    'sync_uuid' => $syncUuid,
                    'sync_status' => 'synced',
                    'created_at' => !empty($orderData['created_at']) ? $orderData['created_at'] : now(),
                ]);

                // Create Order Items and decrement live product inventory
                $items = $orderData['items'] ?? [];
                foreach ($items as $itemData) {
                    $product = null;
                    if (!empty($itemData['sku'])) {
                        $product = Product::where('sku', $itemData['sku'])->first();
                    } elseif (!empty($itemData['product_id'])) {
                        $product = Product::find($itemData['product_id']);
                    }

                    $qty = (int) ($itemData['quantity'] ?? 1);

                    OrderItem::create([
                        'order_id' => $newOrder->id,
                        'product_id' => $product?->id,
                        'product_name' => $itemData['product_name'] ?? ($product?->name ?? 'POS Item'),
                        'sku' => $itemData['sku'] ?? $product?->sku,
                        'pack_size' => $itemData['pack_size'] ?? $product?->pack_size,
                        'unit_price' => (float) ($itemData['unit_price'] ?? 0),
                        'original_price' => (float) ($itemData['original_price'] ?? ($itemData['unit_price'] ?? 0)),
                        'quantity' => $qty,
                        'line_total' => (float) ($itemData['line_total'] ?? 0),
                    ]);

                    // Deduct stock on live database
                    if ($product) {
                        $product->decrement('stock_quantity', $qty);
                    }
                }

                OrderHistory::create([
                    'order_id' => $newOrder->id,
                    'status_from' => null,
                    'status_to' => 'delivered',
                    'comment' => 'Offline POS order synchronized and inventory decremented.',
                ]);

                ActivityLog::log('sync.offline_sale_received', $newOrder, [
                    'order_number' => $newOrder->order_number,
                    'grand_total' => $newOrder->grand_total,
                    'cashier' => $orderData['cashier_name'] ?? 'Offline Cashier',
                    'synced_at' => now()->toIsoString(),
                ]);

                if ($syncUuid) {
                    $syncedUuids[] = $syncUuid;
                }
                $ordersProcessed++;
            }

            // 2. Process Offline Added Products
            foreach ($products as $prodData) {
                $prodSyncUuid = $prodData['sync_uuid'] ?? null;
                $sku = $prodData['sku'] ?? null;
                if (!$sku) {
                    continue;
                }

                // Match or create category
                $catName = $prodData['category_name'] ?? 'General';
                $category = Category::firstOrCreate(
                    ['name' => $catName],
                    ['slug' => Str::slug($catName), 'is_active' => true]
                );

                // Match or create brand
                $brandId = null;
                if (!empty($prodData['brand_name'])) {
                    $brand = Brand::firstOrCreate(
                        ['name' => $prodData['brand_name']],
                        ['slug' => Str::slug($prodData['brand_name']), 'is_active' => true]
                    );
                    $brandId = $brand->id;
                }

                $existingProduct = Product::where('sku', $sku)->first();

                if ($existingProduct) {
                    // Update price or stock if needed
                    $existingProduct->update([
                        'price' => (float) $prodData['price'],
                        'sale_price' => !empty($prodData['sale_price']) ? (float) $prodData['sale_price'] : null,
                        'purchase_price' => (float) ($prodData['purchase_price'] ?? $existingProduct->purchase_price),
                        'stock_quantity' => (int) $prodData['stock_quantity'],
                        'pack_size' => $prodData['pack_size'] ?? $existingProduct->pack_size,
                        'sync_status' => 'synced',
                    ]);
                } else {
                    $slug = Str::slug($prodData['name']) . '-' . Str::lower(Str::random(5));
                    Product::create([
                        'name' => $prodData['name'],
                        'slug' => $slug,
                        'sku' => $sku,
                        'category_id' => $category->id,
                        'brand_id' => $brandId,
                        'price' => (float) $prodData['price'],
                        'sale_price' => !empty($prodData['sale_price']) ? (float) $prodData['sale_price'] : null,
                        'purchase_price' => (float) ($prodData['purchase_price'] ?? 0),
                        'stock_quantity' => (int) $prodData['stock_quantity'],
                        'low_stock_threshold' => (int) ($prodData['low_stock_threshold'] ?? 10),
                        'pack_size' => $prodData['pack_size'] ?? '',
                        'unit_description' => $prodData['unit_description'] ?? '',
                        'weight_volume' => $prodData['weight_volume'] ?? '',
                        'is_active' => true,
                        'sync_uuid' => $prodSyncUuid,
                        'sync_status' => 'synced',
                    ]);
                }

                if ($prodSyncUuid) {
                    $syncedUuids[] = $prodSyncUuid;
                }
                $productsProcessed++;
            }

            // 3. Process Product Expenses
            if (!empty($expenses)) {
                ProductExpense::ensureTableExists();
                foreach ($expenses as $expenseData) {
                    $expSyncUuid = $expenseData['sync_uuid'] ?? null;
                    if ($expSyncUuid && ProductExpense::where('sync_uuid', $expSyncUuid)->exists()) {
                        $syncedUuids[] = $expSyncUuid;
                        continue;
                    }

                    $matchedProduct = Product::where('sku', $expenseData['product_sku'] ?? '')->first();

                    ProductExpense::create([
                        'product_id' => $matchedProduct?->id,
                        'product_name' => $expenseData['product_name'] ?? 'Offline Item',
                        'product_sku' => $expenseData['product_sku'] ?? '',
                        'staff_name' => $expenseData['staff_name'] ?? 'Staff Member',
                        'category' => $expenseData['category'] ?? 'staff_consumption',
                        'quantity' => (int) ($expenseData['quantity'] ?? 1),
                        'unit_cost' => (float) ($expenseData['unit_cost'] ?? 0),
                        'total_cost' => (float) ($expenseData['total_cost'] ?? 0),
                        'reason' => ($expenseData['reason'] ?? '') . ' [Offline POS Synced]',
                        'expense_date' => $expenseData['expense_date'] ?? today()->format('Y-m-d'),
                        'sync_uuid' => $expSyncUuid,
                        'sync_status' => 'synced',
                    ]);

                    if ($expSyncUuid) {
                        $syncedUuids[] = $expSyncUuid;
                    }
                    $expensesProcessed++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'orders_processed' => $ordersProcessed,
                'products_processed' => $productsProcessed,
                'expenses_processed' => $expensesProcessed,
                'synced_uuids' => array_values(array_unique($syncedUuids)),
                'message' => 'Offline batch successfully synced into live database.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Sync Push Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Sync push processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Provide catalog updates (products, prices, categories) to offline workstation.
     */
    public function pull(Request $request): JsonResponse
    {
        if ($error = $this->authenticateSyncToken($request)) {
            return $error;
        }

        $since = $request->query('since');

        $productsQuery = Product::with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true);

        if ($since) {
            $productsQuery->where('updated_at', '>=', $since);
        }

        $products = $productsQuery->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'sku' => $p->sku,
                'category_name' => $p->category?->name ?? 'Uncategorized',
                'category_slug' => $p->category?->slug ?? 'uncategorized',
                'brand_name' => $p->brand?->name ?? null,
                'price' => (float) $p->price,
                'sale_price' => $p->sale_price ? (float) $p->sale_price : null,
                'purchase_price' => (float) ($p->purchase_price ?? 0),
                'stock_quantity' => (int) $p->stock_quantity,
                'low_stock_threshold' => (int) ($p->low_stock_threshold ?? 10),
                'pack_size' => $p->pack_size ?? '',
                'unit_description' => $p->unit_description ?? '',
                'weight_volume' => $p->weight_volume ?? '',
                'is_active' => (bool) $p->is_active,
                'sync_uuid' => $p->sync_uuid,
                'updated_at' => $p->updated_at?->toIsoString(),
            ];
        });

        $categories = Category::where('is_active', true)
            ->select('id', 'name', 'slug', 'is_active', 'sort_order')
            ->get();

        $brands = Brand::where('is_active', true)
            ->select('id', 'name', 'slug', 'is_active')
            ->get();

        return response()->json([
            'success' => true,
            'server_time' => now()->toIsoString(),
            'products_count' => $products->count(),
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
        ]);
    }
}
