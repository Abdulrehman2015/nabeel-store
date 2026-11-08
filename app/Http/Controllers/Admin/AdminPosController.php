<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPosController extends Controller
{
    /**
     * Display the Point of Sale (POS) interface.
     */
    public function index(): View
    {
        // Active products with categories and brands
        $products = Product::where('is_active', true)
            ->with(['category:id,name,slug', 'brand:id,name'])
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku ?? '',
                    'category_id' => $product->category_id,
                    'category_name' => $product->category?->name ?? 'Uncategorized',
                    'brand_name' => $product->brand?->name ?? '',
                    'pack_size' => $product->pack_size ?? '',
                    'unit_description' => $product->unit_description ?? '',
                    'price' => (float) $product->price,
                    'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
                    'effective_price' => (float) ($product->sale_price ?? $product->price),
                    'stock_quantity' => (int) $product->stock_quantity,
                    'has_custom_image' => !empty($product->featured_image),
                    'image_url' => $product->featured_image
                        ? (str_starts_with($product->featured_image, 'http') ? $product->featured_image : asset('storage/' . $product->featured_image))
                        : asset('images/default-product.png'),
                ];
            });

        // Active categories with products for filter tabs
        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->having('products_count', '>', 0)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'products_count' => (int) $cat->products_count,
                    'image_url' => $cat->image_url,
                    'icon' => $cat->icon,
                ];
            });

        // Customers for quick lookup
        $customers = User::where('role', 'customer')
            ->select('id', 'name', 'phone', 'email')
            ->orderBy('name')
            ->get();

        $currentUser = auth()->user();
        $isCashier = !$currentUser->isAdmin();

        // Today's POS statistics query
        $todayPosQuery = Order::whereDate('created_at', today())
            ->where('delivery_zone', 'pos_counter')
            ->where('order_status', 'delivered');

        // Professional Cashier Isolation:
        // If a cashier / non-admin is logged in, strictly count ONLY their own sales
        if ($isCashier) {
            $todayPosQuery->where(function ($q) use ($currentUser) {
                $q->where('cashier_id', $currentUser->id)
                  ->orWhere('payment_verified_by', $currentUser->id);
            });
        } elseif (request()->filled('cashier_id')) {
            // Admin can view specific cashier or all
            $todayPosQuery->where('cashier_id', request()->query('cashier_id'));
        }

        $todayPosOrders = $todayPosQuery->get();

        $todaySales = $todayPosOrders->sum('grand_total');
        $todayTransactions = $todayPosOrders->count();

        // Payment method breakdown for shift balancing
        $cashSales = (clone $todayPosOrders)->where('payment_method', 'cash')->sum('grand_total');
        $qrSales = (clone $todayPosOrders)->where('payment_method', 'duitnow_qr')->sum('grand_total');
        $cardSales = (clone $todayPosOrders)->where('payment_method', 'card')->sum('grand_total');

        // Cashiers list for Admin view switch
        $allCashiers = $currentUser->isAdmin()
            ? User::whereIn('role', ['admin', 'staff', 'cashier'])->select('id', 'name', 'role')->orderBy('name')->get()
            : collect();

        // Active brands for quick product creation
        $brands = \App\Models\Brand::where('is_active', true)->select('id', 'name')->orderBy('name')->get();

        return view('admin.pos.index', compact(
            'products',
            'categories',
            'brands',
            'customers',
            'todaySales',
            'todayTransactions',
            'cashSales',
            'qrSales',
            'cardSales',
            'allCashiers',
            'isCashier'
        ));
    }

    /**
     * Process POS sale checkout.
     */
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.original_price' => 'nullable|numeric|min:0',
            'items.*.discount_price' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:cash,duitnow_qr,card',
            'amount_tendered' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:30',
            'customer_email' => 'nullable|email|max:100',
            'user_id' => 'nullable|integer|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            $productIds = collect($validated['items'])->pluck('id');
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $calculatedSubtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['id']);
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product could not be found.',
                    ], 422);
                }

                if ($product->stock_quantity < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}, requested: {$item['quantity']}.",
                    ], 422);
                }

                $catalogPrice = (float) ($product->sale_price ?? $product->price);
                $originalPrice = isset($item['original_price']) && is_numeric($item['original_price'])
                    ? round((float) $item['original_price'], 2)
                    : $catalogPrice;

                if (isset($item['unit_price']) && is_numeric($item['unit_price']) && (float) $item['unit_price'] >= 0) {
                    $unitPrice = round((float) $item['unit_price'], 2);
                } else {
                    $unitPrice = $catalogPrice;
                }

                $qty = (int) $item['quantity'];
                $lineTotal = round($unitPrice * $qty, 2);
                $calculatedSubtotal += $lineTotal;

                // Decrement inventory in real-time
                $product->decrement('stock_quantity', $qty);

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku ?? null,
                    'pack_size' => $product->pack_size ?? null,
                    'unit_price' => $unitPrice,
                    'original_price' => $originalPrice,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                    'image' => $product->featured_image,
                ];
            }

            $calculatedSubtotal = round($calculatedSubtotal, 2);
            $discountAmount = min($calculatedSubtotal, (float) ($validated['discount_amount'] ?? 0));
            $grandTotal = round(max(0, $calculatedSubtotal - $discountAmount), 2);

            $tendered = (float) ($validated['amount_tendered'] ?? $grandTotal);
            $changeDue = max(0, round($tendered - $grandTotal, 2));

            // Generate POS Order Number
            $year = date('Y');
            $prefix = 'POS-' . $year . '-';
            $latestPos = Order::where('order_number', 'LIKE', $prefix . '%')
                ->lockForUpdate()
                ->latest('id')
                ->first();

            if ($latestPos) {
                $lastNumber = (int) Str::afterLast($latestPos->order_number, '-');
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1001; // Start POS serial at 1001
            }

            $orderNumber = $prefix . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);

            $customerName = trim($validated['customer_name'] ?? '') ?: 'Walk-in Customer';
            $customerPhone = trim($validated['customer_phone'] ?? '') ?: '-';
            $customerEmail = trim($validated['customer_email'] ?? '') ?: 'pos@nandjminimart.com';

            $notes = 'In-store POS counter checkout by ' . auth()->user()->name . '.';
            if (!empty($validated['notes'])) {
                $notes .= ' Note: ' . $validated['notes'];
            }
            if ($validated['payment_method'] === 'cash') {
                $notes .= ' Tendered: RM ' . number_format($tendered, 2) . ', Change: RM ' . number_format($changeDue, 2);
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $validated['user_id'] ?? null,
                'cashier_id' => auth()->id(),
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_email' => $customerEmail,
                'delivery_address' => 'N&J Mini Mart Counter (Walk-in POS)',
                'delivery_city' => 'Puchong',
                'floor' => 0,
                'landmark' => 'In-Store Register #1',
                'order_notes' => $validated['notes'] ?? null,
                'latitude' => (float) setting('store_latitude', 1.5206574),
                'longitude' => (float) setting('store_longitude', 103.6616431),
                'distance_km' => 0.00,
                'delivery_zone' => 'pos_counter',
                'subtotal' => $calculatedSubtotal,
                'floor_charge' => 0.00,
                'delivery_charge' => 0.00,
                'grand_total' => $grandTotal,
                'advance_percentage' => 100.00,
                'advance_amount' => $grandTotal,
                'balance_amount' => 0.00,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => 'POS-' . strtoupper($validated['payment_method']) . '-' . strtoupper(Str::random(6)),
                'payment_proof_path' => null,
                'payment_status' => 'verified',
                'payment_verified_at' => now(),
                'payment_verified_by' => auth()->id(),
                'order_status' => 'delivered',
                'internal_notes' => $notes,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'idempotency_token' => Str::uuid()->toString(),
            ]);

            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            OrderHistory::create([
                'order_id' => $order->id,
                'status_from' => null,
                'status_to' => 'delivered',
                'comment' => 'POS in-store counter sale completed. Items handed to customer.',
                'changed_by' => auth()->id(),
            ]);

            ActivityLog::log('pos.sale_completed', $order, [
                'grand_total' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'tendered' => $tendered,
                'change' => $changeDue,
                'cashier' => auth()->user()->name,
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'grand_total' => $order->grand_total,
                'subtotal' => $calculatedSubtotal,
                'discount' => $discountAmount,
                'tendered' => $tendered,
                'change_due' => $changeDue,
                'payment_method' => $order->payment_method,
                'customer_name' => $order->customer_name,
                'receipt_url' => route('admin.pos.receipt', $order->id),
            ]);
        });
    }

    /**
     * Void / Reopen recent POS counter sale for re-editing.
     */
    public function voidOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::with('items')->find($validated['order_id']);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        // Only allow voiding recent POS counter sales created in the last 30 minutes
        if ($order->created_at->diffInMinutes(now()) > 30) {
            return response()->json(['success' => false, 'message' => 'Order is too old to be reopened for editing.'], 422);
        }

        DB::transaction(function () use ($order) {
            // Restore inventory stock for all items
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }

            ActivityLog::log('pos.order_reopened_reedit', $order, [
                'order_number' => $order->order_number,
                'grand_total' => $order->grand_total,
                'reopened_by' => auth()->user()->name,
            ]);

            // Delete order items, histories and the order
            $order->items()->delete();
            $order->histories()->delete();
            $order->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Order voided and reopened for counter editing.',
        ]);
    }

    /**
     * Display printable thermal POS receipt.
     */
    public function receipt($order): View
    {
        if ($order instanceof Order) {
            $resolvedOrder = $order;
        } else {
            $resolvedOrder = Order::where('id', $order)
                ->orWhere('order_number', $order)
                ->firstOrFail();
        }

        $resolvedOrder->load(['items.product']);

        return view('admin.pos.receipt', ['order' => $resolvedOrder]);
    }

    /**
     * Display a sample printable thermal POS receipt for testing printer slip machines.
     */
    public function testReceipt(Request $request): View
    {
        $dummyOrder = new Order([
            'id' => 99999,
            'order_number' => 'POS-' . date('Y') . '-TEST',
            'customer_name' => 'Walk-in Retail Customer',
            'customer_email' => 'pos@nandjminimart.com',
            'customer_phone' => '+60 12-345 6789',
            'subtotal' => 45.00,
            'discount_amount' => 5.00,
            'delivery_fee' => 0.00,
            'grand_total' => 40.00,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'delivery_zone' => 'pos_counter',
            'delivery_address' => 'N&J Mini Mart Counter (Walk-in POS)',
            'internal_notes' => 'TEST PRINT SLIP: Tendered: 50.00, Change: 10.00',
            'created_at' => now(),
        ]);

        $dummyItems = collect([
            new OrderItem([
                'product_name' => 'AYAM BRAND BAKED BEANS 24*425G',
                'sku' => 'CAN-AYM-01',
                'quantity' => 2,
                'unit_price' => 15.00,
                'line_total' => 30.00,
            ]),
            new OrderItem([
                'product_name' => '100 PLUS ISOTONIC 24*325ML',
                'sku' => 'BEV-100-01',
                'quantity' => 1,
                'unit_price' => 15.00,
                'line_total' => 15.00,
            ]),
        ]);

        $dummyOrder->setRelation('items', $dummyItems);

        return view('admin.pos.receipt', [
            'order' => $dummyOrder,
            'isTest' => true,
        ]);
    }

    /**
     * Quick create customer from POS terminal.
     */
    public function quickCustomer(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30|unique:users,phone',
            'email' => 'nullable|email|max:100|unique:users,email',
        ]);

        $email = $validated['email'] ?? 'cust_' . time() . '@nandjminimart.com';

        $customer = User::create([
            'name' => trim($validated['name']),
            'phone' => trim($validated['phone']),
            'email' => $email,
            'role' => 'customer',
            'is_active' => true,
            'password' => Hash::make(Str::random(16)),
        ]);

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ],
        ]);
    }

    /**
     * Quick add new product directly from POS terminal.
     */
    public function quickProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'price' => 'required|numeric|min:0.01',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'pack_size' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:100|unique:products,sku',
        ]);

        $sku = !empty($validated['sku']) ? trim($validated['sku']) : 'POS-' . strtoupper(Str::random(6));

        $productData = [
            'name' => trim($validated['name']),
            'slug' => Str::slug($validated['name']) . '-' . Str::lower(Str::random(5)),
            'category_id' => (int) $validated['category_id'],
            'brand_id' => !empty($validated['brand_id']) ? (int) $validated['brand_id'] : null,
            'price' => (float) $validated['price'],
            'sale_price' => !empty($validated['sale_price']) ? (float) $validated['sale_price'] : null,
            'stock_quantity' => (int) $validated['stock_quantity'],
            'pack_size' => $validated['pack_size'] ?? '',
            'sku' => $sku,
            'is_active' => true,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'purchase_price')) {
            $productData['purchase_price'] = round($productData['price'] * 0.70, 2);
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold')) {
            $productData['low_stock_threshold'] = 10;
        }

        $product = Product::create($productData);
        $product->load(['category', 'brand']);

        ActivityLog::log('pos.product_quick_added', $product, [
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'stock' => $product->stock_quantity,
            'added_by' => auth()->user()->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Product '{$product->name}' added successfully!",
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? '',
                'category_id' => $product->category_id,
                'category_name' => $product->category?->name ?? 'Uncategorized',
                'brand_name' => $product->brand?->name ?? '',
                'pack_size' => $product->pack_size ?? '',
                'unit_description' => '',
                'price' => (float) $product->price,
                'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
                'effective_price' => (float) ($product->sale_price ?? $product->price),
                'stock_quantity' => (int) $product->stock_quantity,
                'has_custom_image' => false,
                'image_url' => asset('images/default-product.png'),
            ],
        ]);
    }

    /**
     * Real-time Barcode / SKU resolver endpoint.
     * Called automatically by POS if a scanned barcode or SKU is not found in client-side memory.
     */
    public function findBarcode(Request $request): JsonResponse
    {
        $code = trim($request->query('code', ''));
        if ($code === '') {
            return response()->json(['success' => false, 'message' => 'No barcode provided'], 400);
        }

        // Clean barcode: strip AIM symbology prefixes (e.g. ]C1, ]e0, etc.)
        $cleanCode = preg_replace('/^\][a-zA-Z0-9]{2}/', '', $code);
        $cleanCode = trim(preg_replace('/[\r\n\t]/', '', $cleanCode));
        $alphanumeric = preg_replace('/[^a-zA-Z0-9]/', '', $cleanCode);
        $unpadded = ltrim($alphanumeric, '0');

        // Look up product in database by SKU (exact, alphanumeric, leading zero stripped, or ID)
        $product = Product::with(['category:id,name,slug', 'brand:id,name'])
            ->where(function ($q) use ($cleanCode, $alphanumeric, $unpadded) {
                $q->where('sku', $cleanCode)
                  ->orWhere('sku', $alphanumeric)
                  ->orWhereRaw("TRIM(sku) = ?", [$cleanCode])
                  ->orWhereRaw("LOWER(TRIM(sku)) = ?", [strtolower($cleanCode)]);
                
                if (!empty($unpadded)) {
                    $q->orWhereRaw("LTRIM(TRIM(sku), '0') = ?", [$unpadded]);
                }
                if (is_numeric($cleanCode)) {
                    $q->orWhere('id', (int) $cleanCode);
                }
            })
            ->first();

        // Secondary fallback: search with wildcards on SKU or exact name
        if (!$product) {
            $product = Product::with(['category:id,name,slug', 'brand:id,name'])
                ->where('sku', 'LIKE', "%{$cleanCode}%")
                ->orWhere('name', $cleanCode)
                ->first();
        }

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => "No product found in database with barcode/SKU: {$cleanCode}",
                'code' => $cleanCode,
            ], 404);
        }

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku ?? '',
            'category_id' => $product->category_id,
            'category_name' => $product->category?->name ?? 'Uncategorized',
            'brand_name' => $product->brand?->name ?? '',
            'pack_size' => $product->pack_size ?? '',
            'unit_description' => $product->unit_description ?? '',
            'price' => (float) $product->price,
            'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
            'effective_price' => (float) ($product->sale_price ?? $product->price),
            'stock_quantity' => (int) $product->stock_quantity,
            'is_active' => (bool) $product->is_active,
            'has_custom_image' => !empty($product->featured_image),
            'image_url' => $product->featured_image
                ? (str_starts_with($product->featured_image, 'http') ? $product->featured_image : asset('storage/' . $product->featured_image))
                : asset('images/default-product.png'),
        ];

        return response()->json([
            'success' => true,
            'product' => $productData,
            'is_active' => (bool) $product->is_active,
            'stock_quantity' => (int) $product->stock_quantity,
        ]);
    }

    /**
     * Live sync of all active products to update client-side POS state without page refresh.
     */
    public function syncProducts(): JsonResponse
    {
        $products = Product::where('is_active', true)
            ->with(['category:id,name,slug', 'brand:id,name'])
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku ?? '',
                    'category_id' => $product->category_id,
                    'category_name' => $product->category?->name ?? 'Uncategorized',
                    'brand_name' => $product->brand?->name ?? '',
                    'pack_size' => $product->pack_size ?? '',
                    'unit_description' => $product->unit_description ?? '',
                    'price' => (float) $product->price,
                    'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
                    'effective_price' => (float) ($product->sale_price ?? $product->price),
                    'stock_quantity' => (int) $product->stock_quantity,
                    'has_custom_image' => !empty($product->featured_image),
                    'image_url' => $product->featured_image
                        ? (str_starts_with($product->featured_image, 'http') ? $product->featured_image : asset('storage/' . $product->featured_image))
                        : asset('images/default-product.png'),
                ];
            });

        return response()->json([
            'success' => true,
            'total' => $products->count(),
            'products' => $products,
        ]);
    }

    /**
     * Download Windows POS desktop installer.
     * Serves via PHP stream to bypass shared hosting static .exe blocking.
     */
    public function downloadApp()
    {
        $path = public_path('downloads/setup.dat');
        if (!file_exists($path)) {
            $path = base_path('windows-pos-app/N_I_POS_Setup_v1.0.exe');
        }

        if (!file_exists($path)) {
            abort(404, 'Setup installer not found.');
        }

        return response()->download($path, 'N_I_POS_Setup_v1.0.exe', [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="N_I_POS_Setup_v1.0.exe"',
        ]);
    }

    /**
     * Download Windows POS setup package as ZIP.
     */
    public function downloadZip()
    {
        $path = public_path('downloads/NI_POS_Windows_Setup.zip');
        if (!file_exists($path)) {
            $path = public_path('downloads/setup.dat');
        }

        if (!file_exists($path)) {
            abort(404, 'Setup package not found.');
        }

        return response()->download($path, 'NI_POS_Windows_Setup.zip', [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="NI_POS_Windows_Setup.zip"',
        ]);
    }

    /**
     * Cashier shift summary data & drawer balancing.
     */
    public function shiftSummary(): JsonResponse
    {
        $user = auth()->user();
        $today = today();

        $query = Order::whereDate('created_at', $today)
            ->where('delivery_zone', 'pos_counter')
            ->where('order_status', 'delivered');

        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('cashier_id', $user->id)
                  ->orWhere('payment_verified_by', $user->id);
            });
        }

        $orders = $query->with('items')->latest()->get();

        $cash = (clone $orders)->where('payment_method', 'cash')->sum('grand_total');
        $qr = (clone $orders)->where('payment_method', 'duitnow_qr')->sum('grand_total');
        $card = (clone $orders)->where('payment_method', 'card')->sum('grand_total');
        $total = $orders->sum('grand_total');

        return response()->json([
            'cashier_name' => $user->name,
            'shift_date' => now()->format('d M Y'),
            'total_transactions' => $orders->count(),
            'total_sales' => $total,
            'cash_sales' => $cash,
            'qr_sales' => $qr,
            'card_sales' => $card,
            'orders' => $orders->map(function ($o) {
                return [
                    'id' => $o->id,
                    'order_number' => $o->order_number,
                    'customer' => $o->customer_name,
                    'time' => $o->created_at->format('h:i A'),
                    'method' => strtoupper($o->payment_method),
                    'total' => (float) $o->grand_total,
                    'items_count' => $o->items->sum('quantity'),
                    'receipt_url' => route('admin.pos.receipt', $o->id),
                ];
            }),
        ]);
    }
}

