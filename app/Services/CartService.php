<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'bulk_store_cart';

    /**
     * Retrieve all items in the cart with fresh product models.
     */
    public function getItems(): array
    {
        $rawCart = Session::get(self::SESSION_KEY, []);
        $items = [];

        if (empty($rawCart)) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($rawCart))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        foreach ($rawCart as $productId => $qty) {
            if ($products->has($productId)) {
                $product = $products->get($productId);
                $quantity = min((int) $qty, $product->stock_quantity > 0 ? $product->stock_quantity : 1);
                $unitPrice = $product->current_price;
                $lineTotal = round($unitPrice * $quantity, 2);

                $items[$productId] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'slug' => $product->slug,
                    'pack_size' => $product->pack_size,
                    'unit_description' => $product->unit_description,
                    'image' => $product->image_url,
                    'unit_price' => $unitPrice,
                    'original_price' => (float) $product->price,
                    'is_on_sale' => $product->is_on_sale,
                    'stock_quantity' => $product->stock_quantity,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }
        }

        return $items;
    }

    /**
     * Add product to cart.
     */
    public function add(int $productId, int $quantity = 1): array
    {
        $product = Product::where('id', $productId)->where('is_active', true)->firstOrFail();
        $cart = Session::get(self::SESSION_KEY, []);

        $currentQty = $cart[$productId] ?? 0;
        $newQty = $currentQty + max(1, $quantity);

        // Cap to available stock
        if ($newQty > $product->stock_quantity) {
            $newQty = max(1, $product->stock_quantity);
        }

        $cart[$productId] = $newQty;
        Session::put(self::SESSION_KEY, $cart);

        return $this->getSummary();
    }

    /**
     * Update quantity of a product in the cart.
     */
    public function update(int $productId, int $quantity): array
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::find($productId);
            if ($product && $product->is_active) {
                $cart[$productId] = min($quantity, max(1, $product->stock_quantity));
            } else {
                unset($cart[$productId]);
            }
        }

        Session::put(self::SESSION_KEY, $cart);
        return $this->getSummary();
    }

    /**
     * Remove product from cart.
     */
    public function remove(int $productId): array
    {
        $cart = Session::get(self::SESSION_KEY, []);
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);

        return $this->getSummary();
    }

    /**
     * Clear the whole cart.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Calculate cart subtotal.
     */
    public function getSubtotal(): float
    {
        $items = $this->getItems();
        $subtotal = 0.00;
        foreach ($items as $item) {
            $subtotal += $item['line_total'];
        }
        return round($subtotal, 2);
    }

    /**
     * Get total item count.
     */
    public function getItemCount(): int
    {
        $items = $this->getItems();
        $count = 0;
        foreach ($items as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public function getMinimumOrderValue(): float
    {
        return (float) setting('min_order_value', 50.00);
    }

    public function isMinimumMet(): bool
    {
        return $this->getSubtotal() >= $this->getMinimumOrderValue();
    }

    /**
     * Get cart summary suitable for AJAX responses and views.
     */
    public function getSummary(): array
    {
        $items = $this->getItems();
        $subtotal = $this->getSubtotal();
        $minOrder = $this->getMinimumOrderValue();
        $isMinMet = $subtotal >= $minOrder;
        $deficit = max(0, round($minOrder - $subtotal, 2));

        return [
            'items' => array_values($items),
            'item_count' => $this->getItemCount(),
            'subtotal' => $subtotal,
            'subtotal_formatted' => format_currency($subtotal),
            'min_order_value' => $minOrder,
            'min_order_formatted' => format_currency($minOrder),
            'is_minimum_met' => $isMinMet,
            'deficit' => $deficit,
            'deficit_formatted' => format_currency($deficit),
        ];
    }
}

