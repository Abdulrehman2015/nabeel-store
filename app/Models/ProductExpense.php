<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ProductExpense extends Model
{
    use HasFactory;

    protected $table = 'product_expenses';

    protected $fillable = [
        'product_id',
        'product_name',
        'product_sku',
        'user_id',
        'staff_name',
        'category',
        'quantity',
        'unit_cost',
        'total_cost',
        'reason',
        'expense_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'expense_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ensure table exists on shared hosting.
     */
    public static function ensureTableExists(): void
    {
        try {
            if (!Schema::hasTable('product_expenses')) {
                Schema::create('product_expenses', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('product_id')->nullable()->index();
                    $table->string('product_name');
                    $table->string('product_sku')->nullable()->index();
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                    $table->string('staff_name')->nullable();
                    $table->string('category', 50)->default('staff_consumption'); // staff_consumption, store_use, damaged_expired, other
                    $table->integer('quantity')->default(1);
                    $table->decimal('unit_cost', 10, 2)->default(0.00);
                    $table->decimal('total_cost', 10, 2)->default(0.00);
                    $table->string('reason')->nullable();
                    $table->date('expense_date')->index();
                    $table->timestamps();

                    $table->index(['expense_date', 'category']);
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Ensure product_expenses table notice: ' . $e->getMessage());
        }
    }

    /**
     * Record a product consumption expense and automatically deduct stock.
     */
    public static function recordConsumption(array $data): self
    {
        self::ensureTableExists();

        $product = Product::findOrFail($data['product_id']);
        $qty = max(1, (int) ($data['quantity'] ?? 1));

        // Use purchase_price if available, otherwise fallback to retail price
        $unitCost = isset($data['unit_cost']) && $data['unit_cost'] !== ''
            ? (float) $data['unit_cost']
            : (float) ($product->purchase_price > 0 ? $product->purchase_price : $product->price);

        $totalCost = round($qty * $unitCost, 2);

        $expense = self::create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku ?? '',
            'user_id' => $data['user_id'] ?? auth()->id(),
            'staff_name' => $data['staff_name'] ?? (auth()->user()?->name ?? 'Staff / Owner'),
            'category' => $data['category'] ?? 'staff_consumption',
            'quantity' => $qty,
            'unit_cost' => $unitCost,
            'total_cost' => $totalCost,
            'reason' => $data['reason'] ?? 'Internal staff/owner use',
            'expense_date' => $data['expense_date'] ?? Carbon::today(),
        ]);

        // Deduct inventory stock
        $product->decrement('stock_quantity', $qty);

        // Check for low-stock notification
        try {
            $threshold = (int) ($product->low_stock_threshold ?? 10);
            if ($product->stock_quantity <= $threshold) {
                AdminNotification::send(
                    'inventory',
                    "Stock Deducted for Internal Use: {$product->name}",
                    "{$qty} item(s) used by {$expense->staff_name}. Current remaining stock is now {$product->stock_quantity}.",
                    [
                        'level' => $product->stock_quantity <= 0 ? 'danger' : 'warning',
                        'url' => route('admin.products.edit', $product->id),
                        'action_text' => 'Restock Product',
                        'data' => [
                            'product_id' => $product->id,
                            'stock_quantity' => $product->stock_quantity,
                        ],
                    ]
                );
            }
        } catch (\Throwable $e) {}

        return $expense;
    }
}
