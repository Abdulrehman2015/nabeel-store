<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected DeliveryService $deliveryService,
        protected CartService $cartService
    ) {}

    /**
     * Generate unique sequential human-friendly order number: e.g. BS-2026-000101.
     */
    public function generateOrderNumber(): string
    {
        $year = date('Y');
        $prefix = setting('order_number_prefix', 'BS') . '-' . $year . '-';

        return DB::transaction(function () use ($prefix) {
            $latestOrder = Order::where('order_number', 'LIKE', $prefix . '%')
                ->lockForUpdate()
                ->latest('id')
                ->first();

            if ($latestOrder) {
                $lastNumber = (int) Str::afterLast($latestOrder->order_number, '-');
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 101; // Start at 101 for professional feel
            }

            return $prefix . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Create order after payment proof submission.
     * Enforces server-side recalculation, row locking for stock, and idempotency.
     */
    public function createOrderWithProof(array $data, UploadedFile $paymentProof): Order
    {
        return DB::transaction(function () use ($data, $paymentProof) {
            // 1. Check idempotency token to prevent double-submit
            if (!empty($data['idempotency_token'])) {
                $existing = Order::where('idempotency_token', $data['idempotency_token'])->first();
                if ($existing) {
                    return $existing;
                }
            }

            // 2. Prevent duplicate payment reference
            $refExists = Order::where('payment_reference', trim($data['payment_reference']))->exists();
            if ($refExists) {
                throw new \InvalidArgumentException('This Transaction / Reference ID has already been submitted for another order.');
            }

            // 3. Fetch cart items & lock products for stock verification
            $cartItems = $this->cartService->getItems();
            if (empty($cartItems)) {
                throw new \UnderflowException('Your cart is empty. Please add items before checking out.');
            }

            $productIds = array_column($cartItems, 'product_id');
            $products = Product::whereIn('id', $productIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 4. Server-side recalculation of subtotal and stock check
            $calculatedSubtotal = 0.00;
            $verifiedItems = [];

            foreach ($cartItems as $cartItem) {
                $productId = $cartItem['product_id'];
                if (!$products->has($productId)) {
                    throw new \RuntimeException('Product "' . $cartItem['name'] . '" is no longer available.');
                }

                $product = $products->get($productId);
                if ($product->stock_quantity < $cartItem['quantity']) {
                    throw new \RuntimeException('Insufficient stock for "' . $product->name . '". Only ' . $product->stock_quantity . ' available.');
                }

                $unitPrice = $product->current_price;
                $lineTotal = round($unitPrice * $cartItem['quantity'], 2);
                $calculatedSubtotal += $lineTotal;

                $verifiedItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'pack_size' => $product->pack_size,
                    'unit_price' => $unitPrice,
                    'quantity' => $cartItem['quantity'],
                    'line_total' => $lineTotal,
                    'image' => $product->featured_image,
                ];
            }

            $calculatedSubtotal = round($calculatedSubtotal, 2);

            // Minimum order value validation
            $minOrder = (float) setting('min_order_value', 50.00);
            if ($calculatedSubtotal < $minOrder) {
                throw new \InvalidArgumentException('Minimum order value of ' . format_currency($minOrder) . ' is not met.');
            }

            // 5. Server-side delivery & floor calculation (Never trust client values)
            $floor = max(0, (int) ($data['floor'] ?? 0));
            $deliveryEval = $this->deliveryService->evaluateDelivery(
                (float) $data['latitude'],
                (float) $data['longitude'],
                $floor,
                $calculatedSubtotal
            );

            if (!$deliveryEval['eligible']) {
                throw new \InvalidArgumentException($deliveryEval['message']);
            }

            $deliveryCharge = $deliveryEval['delivery_charge'];
            $floorCharge = $deliveryEval['floor_charge'];
            $grandTotal = round($calculatedSubtotal + $deliveryCharge + $floorCharge, 2);

            // 6. Calculate Advance Payment & Balance
            $advancePercentage = (float) setting('advance_percentage', 10.00);
            $requireAdvance = (bool) setting('require_advance_payment', true);

            if ($requireAdvance && $advancePercentage > 0) {
                $advanceAmount = round(($grandTotal * $advancePercentage) / 100, 2);
            } else {
                $advancePercentage = 0.00;
                $advanceAmount = 0.00;
            }

            $balanceAmount = round($grandTotal - $advanceAmount, 2);

            // 7. Store payment proof securely on private disk
            $filename = 'proof_' . date('Ymd_His') . '_' . Str::random(16) . '.' . $paymentProof->getClientOriginalExtension();
            $proofPath = $paymentProof->storeAs('payment_proofs', $filename, 'local'); // 'local' is private (outside public storage)

            // 8. Create Order Record
            $orderNumber = $this->generateOrderNumber();

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $data['user_id'] ?? auth()->id() ?? null,
                'customer_name' => trim($data['customer_name']),
                'customer_phone' => trim($data['customer_phone']),
                'customer_email' => trim($data['customer_email']),
                'delivery_address' => trim($data['delivery_address']),
                'delivery_city' => trim($data['delivery_city'] ?? 'Kuala Lumpur'),
                'floor' => $floor,
                'landmark' => $data['landmark'] ?? null,
                'order_notes' => $data['order_notes'] ?? null,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'distance_km' => $deliveryEval['distance_km'],
                'delivery_zone' => $deliveryEval['zone'],
                'subtotal' => $calculatedSubtotal,
                'floor_charge' => $floorCharge,
                'delivery_charge' => $deliveryCharge,
                'grand_total' => $grandTotal,
                'advance_percentage' => $advancePercentage,
                'advance_amount' => $advanceAmount,
                'balance_amount' => $balanceAmount,
                'payment_method' => 'qr_advance',
                'payment_reference' => trim($data['payment_reference']),
                'payment_proof_path' => $proofPath,
                'payment_status' => 'pending_verification',
                'order_status' => 'pending_payment_verification',
                'accepted_policy_versions' => $data['accepted_policy_versions'] ?? null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'idempotency_token' => $data['idempotency_token'] ?? Str::uuid()->toString(),
            ]);

            // 9. Save Order Items
            foreach ($verifiedItems as $itemData) {
                $order->items()->create($itemData);
            }

            // 10. Record Order History
            OrderHistory::create([
                'order_id' => $order->id,
                'status_from' => null,
                'status_to' => 'pending_payment_verification',
                'comment' => 'Order placed with payment proof (Ref: ' . $order->payment_reference . '). Awaiting admin verification.',
                'changed_by' => auth()->id() ?? null,
            ]);

            // 11. Clear Cart
            $this->cartService->clear();

            ActivityLog::log('order.created', $order, [
                'order_number' => $order->order_number,
                'grand_total' => $grandTotal,
                'advance_amount' => $advanceAmount,
            ]);

            return $order;
        });
    }

    /**
     * Admin approves/verifies advance payment.
     * Order becomes 'confirmed' and stock is officially deducted.
     */
    public function verifyPayment(Order $order, int $adminUserId, ?string $note = null): Order
    {
        return DB::transaction(function () use ($order, $adminUserId, $note) {
            $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

            if ($order->order_status !== 'pending_payment_verification' && $order->order_status !== 'payment_rejected') {
                throw new \InvalidArgumentException('Order is already in ' . $order->status_label . ' status.');
            }

            // Deduct stock for all items
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->decrement('stock_quantity', $item->quantity);
                    }
                }
            }

            $oldStatus = $order->order_status;
            $order->update([
                'payment_status' => 'verified',
                'order_status' => 'confirmed',
                'payment_verified_at' => now(),
                'payment_verified_by' => $adminUserId,
                'internal_notes' => $note ? ($order->internal_notes ? $order->internal_notes . "\n" . $note : $note) : $order->internal_notes,
            ]);

            OrderHistory::create([
                'order_id' => $order->id,
                'status_from' => $oldStatus,
                'status_to' => 'confirmed',
                'comment' => 'Payment verified by admin. ' . ($note ? 'Note: ' . $note : ''),
                'changed_by' => $adminUserId,
            ]);

            ActivityLog::log('payment.verified', $order, ['order_number' => $order->order_number], $adminUserId);

            return $order;
        });
    }

    /**
     * Admin rejects payment proof with a reason.
     */
    public function rejectPayment(Order $order, int $adminUserId, string $reason): Order
    {
        return DB::transaction(function () use ($order, $adminUserId, $reason) {
            $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();
            $oldStatus = $order->order_status;

            // If it was confirmed previously and stock was deducted, replenish stock
            if ($oldStatus === 'confirmed') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->increment('stock_quantity', $item->quantity);
                        }
                    }
                }
            }

            $order->update([
                'payment_status' => 'rejected',
                'order_status' => 'payment_rejected',
                'payment_rejection_reason' => $reason,
            ]);

            OrderHistory::create([
                'order_id' => $order->id,
                'status_from' => $oldStatus,
                'status_to' => 'payment_rejected',
                'comment' => 'Payment rejected: ' . $reason,
                'changed_by' => $adminUserId,
            ]);

            ActivityLog::log('payment.rejected', $order, ['reason' => $reason], $adminUserId);

            return $order;
        });
    }

    /**
     * Update order status (Processing, Out for Delivery, Delivered, Cancelled).
     */
    public function updateStatus(Order $order, string $newStatus, int $adminUserId, ?string $comment = null): Order
    {
        return DB::transaction(function () use ($order, $newStatus, $adminUserId, $comment) {
            $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();
            $oldStatus = $order->order_status;

            if ($oldStatus === $newStatus) {
                return $order;
            }

            // If cancelling an order that was confirmed/processing, replenish stock
            if ($newStatus === 'cancelled' && in_array($oldStatus, ['confirmed', 'processing', 'out_for_delivery'])) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->increment('stock_quantity', $item->quantity);
                        }
                    }
                }
            }

            $order->update(['order_status' => $newStatus]);

            OrderHistory::create([
                'order_id' => $order->id,
                'status_from' => $oldStatus,
                'status_to' => $newStatus,
                'comment' => $comment ?: ('Status changed to ' . ucfirst(str_replace('_', ' ', $newStatus))),
                'changed_by' => $adminUserId,
            ]);

            ActivityLog::log('order.status_updated', $order, [
                'from' => $oldStatus,
                'to' => $newStatus,
            ], $adminUserId);

            return $order;
        });
    }
}
