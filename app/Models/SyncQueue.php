<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SyncQueue extends Model
{
    use HasFactory;

    protected $table = 'sync_queues';

    protected $fillable = [
        'sync_uuid',
        'record_type',
        'record_id',
        'action',
        'payload',
        'status',
        'attempts',
        'error_message',
        'synced_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'synced_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * Scope for pending sync tasks.
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'failed'])
            ->where('attempts', '<', 5)
            ->orderBy('id', 'asc');
    }

    /**
     * Ensure table exists on hosting environments that bypass migrations.
     */
    public static function ensureTableExists(): void
    {
        try {
            if (!Schema::hasTable('sync_queues')) {
                Schema::create('sync_queues', function ($table) {
                    $table->id();
                    $table->string('sync_uuid', 64)->unique()->index();
                    $table->string('record_type', 50)->index();
                    $table->unsignedBigInteger('record_id')->nullable()->index();
                    $table->string('action', 20)->default('created');
                    $table->longText('payload');
                    $table->string('status', 20)->default('pending')->index();
                    $table->unsignedSmallInteger('attempts')->default(0);
                    $table->text('error_message')->nullable();
                    $table->timestamp('synced_at')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {
            // Fail safely if database user lacks DDL privileges
        }
    }

    /**
     * Queue an order for synchronization to live server.
     */
    public static function queueOrder(Order $order): self
    {
        self::ensureTableExists();

        $order->loadMissing(['items']);

        $uuid = $order->sync_uuid ?: ($order->idempotency_token ?: Str::uuid()->toString());
        if (!$order->sync_uuid) {
            $order->updateQuietly(['sync_uuid' => $uuid, 'sync_status' => 'pending']);
        }

        // Format full payload so the live server can reconstruct the complete order & items
        $payload = [
            'sync_uuid' => $uuid,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'customer_email' => $order->customer_email,
            'delivery_address' => $order->delivery_address,
            'delivery_city' => $order->delivery_city,
            'delivery_zone' => $order->delivery_zone,
            'subtotal' => (float) $order->subtotal,
            'grand_total' => (float) $order->grand_total,
            'discount_amount' => (float) ($order->discount_amount ?? 0),
            'payment_method' => $order->payment_method,
            'payment_reference' => $order->payment_reference,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'internal_notes' => $order->internal_notes,
            'idempotency_token' => $order->idempotency_token,
            'created_at' => $order->created_at?->toISOString() ?? now()->toISOString(),
            'cashier_id' => $order->cashier_id,
            'cashier_name' => auth()->user()?->name ?? 'Cashier',
            'items' => $order->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'sku' => $item->sku,
                    'pack_size' => $item->pack_size,
                    'unit_price' => (float) $item->unit_price,
                    'original_price' => (float) ($item->original_price ?? $item->unit_price),
                    'quantity' => (int) $item->quantity,
                    'line_total' => (float) $item->line_total,
                ];
            })->toArray(),
        ];

        return self::updateOrCreate(
            ['sync_uuid' => $uuid],
            [
                'record_type' => 'order',
                'record_id' => $order->id,
                'action' => 'created',
                'payload' => $payload,
                'status' => 'pending',
                'attempts' => 0,
            ]
        );
    }

    /**
     * Queue a product for synchronization to live server.
     */
    public static function queueProduct(Product $product, string $action = 'created'): self
    {
        self::ensureTableExists();

        $uuid = $product->sync_uuid ?: Str::uuid()->toString();
        if (!$product->sync_uuid) {
            $product->updateQuietly(['sync_uuid' => $uuid, 'sync_status' => 'pending']);
        }

        $payload = [
            'sync_uuid' => $uuid,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'category_name' => $product->category?->name ?? 'Uncategorized',
            'category_slug' => $product->category?->slug ?? 'uncategorized',
            'brand_name' => $product->brand?->name ?? null,
            'price' => (float) $product->price,
            'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
            'purchase_price' => (float) ($product->purchase_price ?? 0),
            'stock_quantity' => (int) $product->stock_quantity,
            'low_stock_threshold' => (int) ($product->low_stock_threshold ?? 10),
            'pack_size' => $product->pack_size ?? '',
            'unit_description' => $product->unit_description ?? '',
            'weight_volume' => $product->weight_volume ?? '',
            'is_active' => (bool) $product->is_active,
            'updated_at' => $product->updated_at?->toISOString() ?? now()->toISOString(),
        ];

        return self::updateOrCreate(
            ['sync_uuid' => $uuid],
            [
                'record_type' => 'product',
                'record_id' => $product->id,
                'action' => $action,
                'payload' => $payload,
                'status' => 'pending',
                'attempts' => 0,
            ]
        );
    }

    /**
     * Queue a product expense for synchronization to live server.
     */
    public static function queueExpense(ProductExpense $expense): self
    {
        self::ensureTableExists();

        $uuid = $expense->sync_uuid ?: Str::uuid()->toString();
        if (!$expense->sync_uuid) {
            $expense->updateQuietly(['sync_uuid' => $uuid, 'sync_status' => 'pending']);
        }

        $payload = [
            'sync_uuid' => $uuid,
            'product_name' => $expense->product_name,
            'product_sku' => $expense->product_sku,
            'staff_name' => $expense->staff_name,
            'category' => $expense->category,
            'quantity' => (int) $expense->quantity,
            'unit_cost' => (float) $expense->unit_cost,
            'total_cost' => (float) $expense->total_cost,
            'reason' => $expense->reason,
            'expense_date' => $expense->expense_date?->format('Y-m-d') ?? today()->format('Y-m-d'),
        ];

        return self::updateOrCreate(
            ['sync_uuid' => $uuid],
            [
                'record_type' => 'product_expense',
                'record_id' => $expense->id,
                'action' => 'created',
                'payload' => $payload,
                'status' => 'pending',
                'attempts' => 0,
            ]
        );
    }

    /**
     * Mark a record as successfully synced.
     */
    public function markSynced(): void
    {
        $this->update([
            'status' => 'synced',
            'error_message' => null,
            'synced_at' => now(),
        ]);

        // Update corresponding source record status
        if ($this->record_type === 'order' && $this->record_id) {
            Order::where('id', $this->record_id)->update(['sync_status' => 'synced']);
        } elseif ($this->record_type === 'product' && $this->record_id) {
            Product::where('id', $this->record_id)->update(['sync_status' => 'synced']);
        }
    }

    /**
     * Mark a record as failed with error.
     */
    public function markFailed(string $error): void
    {
        $this->increment('attempts');
        $this->update([
            'status' => 'failed',
            'error_message' => Str::limit($error, 1000),
        ]);
    }
}
