<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AdminNotification extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications';

    protected $fillable = [
        'type',
        'level',
        'title',
        'message',
        'url',
        'action_text',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for today's notifications.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Scope for yesterday's notifications.
     */
    public function scopeYesterday($query)
    {
        return $query->whereDate('created_at', Carbon::yesterday());
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Ensure the admin_notifications table exists in the database.
     */
    public static function ensureTableExists(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('admin_notifications')) {
                \Illuminate\Support\Facades\Schema::create('admin_notifications', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('type', 40)->default('system');
                    $table->string('level', 20)->default('info');
                    $table->string('title');
                    $table->text('message');
                    $table->string('url')->nullable();
                    $table->string('action_text', 50)->nullable();
                    $table->json('data')->nullable();
                    $table->boolean('is_read')->default(false);
                    $table->timestamp('read_at')->nullable();
                    $table->timestamps();

                    $table->index('type');
                    $table->index('is_read');
                    $table->index('created_at');
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Ensure admin_notifications table notice: ' . $e->getMessage());
        }
    }

    /**
     * Auto-purge all notifications older than $days (default: 30 days).
     */
    public static function purgeExpired(int $days = 30): int
    {
        self::ensureTableExists();
        return (int) self::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Helper to dispatch an in-app notification.
     */
    public static function send(string $type, string $title, string $message, array $options = []): self
    {
        // Enforce 30-day auto-purge on creation
        self::purgeExpired(30);

        return self::create([
            'type' => $type,
            'level' => $options['level'] ?? 'info',
            'title' => $title,
            'message' => $message,
            'url' => $options['url'] ?? null,
            'action_text' => $options['action_text'] ?? null,
            'data' => $options['data'] ?? null,
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Mark this notification as read.
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark this notification as unread.
     */
    public function markAsUnread(): bool
    {
        return $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Auto-sync today's orders & inventory alerts into the notification feed.
     */
    public static function syncDailyAlerts(): void
    {
        try {
            self::purgeExpired(30);

            // 1. Sync today's online orders
            $todayOrders = Order::whereDate('created_at', Carbon::today())
                ->where('delivery_zone', '!=', 'pos_counter')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->get();

            foreach ($todayOrders as $order) {
                $exists = self::where('type', 'order')
                    ->whereJsonContains('data->order_id', $order->id)
                    ->exists();

                if (!$exists) {
                    self::create([
                        'type' => 'order',
                        'level' => 'success',
                        'title' => "New Online Order #{$order->order_number}",
                        'message' => "Customer {$order->customer_name} placed an order for RM " . number_format((float) $order->grand_total, 2) . " via " . ($order->payment_method ?? 'Online') . ".",
                        'url' => route('admin.orders.show', $order->id),
                        'action_text' => 'View Order',
                        'data' => [
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'grand_total' => (float) $order->grand_total,
                        ],
                        'is_read' => false,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ]);
                }

                // If awaiting payment proof
                if ($order->order_status === 'pending_payment_verification') {
                    $paymentAlertExists = self::where('type', 'payment')
                        ->whereJsonContains('data->order_id', $order->id)
                        ->exists();

                    if (!$paymentAlertExists) {
                        self::create([
                            'type' => 'payment',
                            'level' => 'warning',
                            'title' => "Payment Review Needed: #{$order->order_number}",
                            'message' => "Payment slip uploaded for Order #{$order->order_number} (RM " . number_format((float) $order->grand_total, 2) . "). Admin sign-off required.",
                            'url' => route('admin.orders.show', $order->id),
                            'action_text' => 'Verify Receipt',
                            'data' => [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                            ],
                            'is_read' => false,
                            'created_at' => $order->created_at,
                            'updated_at' => $order->updated_at,
                        ]);
                    }
                }
            }

            // 2. Sync low stock alerts for today (1 notification per low-stock product per day)
            $hasThreshold = \Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold');
            $rawCondition = $hasThreshold ? 'stock_quantity <= COALESCE(low_stock_threshold, 10)' : 'stock_quantity <= 10';

            $lowStockProducts = Product::where('is_active', true)
                ->whereRaw($rawCondition)
                ->get();

            foreach ($lowStockProducts as $prod) {
                $todayAlertExists = self::where('type', 'inventory')
                    ->whereDate('created_at', Carbon::today())
                    ->whereJsonContains('data->product_id', $prod->id)
                    ->exists();

                if (!$todayAlertExists) {
                    $threshold = (int) ($prod->low_stock_threshold ?? 10);
                    $stock = (int) $prod->stock_quantity;
                    $level = $stock <= 0 ? 'danger' : 'warning';
                    $status = $stock <= 0 ? 'OUT OF STOCK' : 'LOW STOCK';

                    self::create([
                        'type' => 'inventory',
                        'level' => $level,
                        'title' => "{$status}: {$prod->name}",
                        'message' => "Product has {$stock} unit(s) remaining (threshold limit is {$threshold}). Restock recommended.",
                        'url' => route('admin.products.edit', $prod->id),
                        'action_text' => 'Restock Product',
                        'data' => [
                            'product_id' => $prod->id,
                            'sku' => $prod->sku,
                            'stock_quantity' => $stock,
                            'low_stock_threshold' => $threshold,
                        ],
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Daily alerts sync notice: ' . $e->getMessage());
        }
    }
}

