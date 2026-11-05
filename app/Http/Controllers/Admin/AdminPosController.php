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
                    'image_url' => $product->featured_image
                        ? (str_starts_with($product->featured_image, 'http') ? $product->featured_image : asset('storage/' . $product->featured_image))
                        : asset('images/logo.png'),
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

        // Today's POS statistics
        $todayPosOrders = Order::whereDate('created_at', today())
            ->where('delivery_zone', 'pos_counter')
            ->where('order_status', 'delivered')
            ->get();

        $todaySales = $todayPosOrders->sum('grand_total');
        $todayTransactions = $todayPosOrders->count();

        return view('admin.pos.index', compact(
            'products',
            'categories',
            'customers',
            'todaySales',
            'todayTransactions'
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

                $unitPrice = (float) ($product->sale_price ?? $product->price);
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
     * Display printable thermal POS receipt.
     */
    public function receipt(Order $order): View
    {
        $order->load(['items.product']);

        return view('admin.pos.receipt', compact('order'));
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
}

