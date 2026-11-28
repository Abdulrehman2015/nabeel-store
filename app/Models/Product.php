<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'pack_size',
        'unit_description',
        'weight_volume',
        'price',
        'purchase_price',
        'sale_price',
        'stock_quantity',
        'low_stock_threshold',
        'short_description',
        'description',
        'featured_image',
        'is_active',
        'is_featured',
        'is_new',
        'is_promo',
        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'focus_keyword',
        'schema_type',
    ];

    protected $attributes = [
        'pack_size' => '',
        'unit_description' => '',
        'weight_volume' => '',
        'short_description' => '',
        'description' => '',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_promo' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopePromo($query)
    {
        return $query->where('is_promo', true)->where('is_active', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true)->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        $hasThreshold = \Illuminate\Support\Facades\Schema::hasColumn('products', 'low_stock_threshold');
        return $query->where('is_active', true)
            ->whereRaw($hasThreshold ? 'stock_quantity <= COALESCE(low_stock_threshold, 10)' : 'stock_quantity <= 10')
            ->where('stock_quantity', '>', 0);
    }

    public function getEffectiveLowStockThresholdAttribute(): int
    {
        return (int) ($this->low_stock_threshold ?? 10);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity <= $this->effective_low_stock_threshold && $this->stock_quantity > 0;
    }

    public function getCurrentPriceAttribute(): float
    {
        if ($this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price) {
            return (float) $this->sale_price;
        }
        return (float) $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->is_on_sale || $this->price <= 0) {
            return 0;
        }
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getUnitProfitAttribute(): float
    {
        $cost = (float) ($this->purchase_price ?? 0);
        return round($this->current_price - $cost, 2);
    }

    public function getMarginPercentAttribute(): int
    {
        if ($this->current_price <= 0) {
            return 0;
        }
        return (int) round(($this->unit_profit / $this->current_price) * 100);
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
                return $this->featured_image;
            }
            $cleanPath = ltrim($this->featured_image, '/');
            if (str_starts_with($cleanPath, 'images/')) {
                return asset($cleanPath);
            }
            $relPath = str_starts_with($cleanPath, 'storage/') ? substr($cleanPath, 8) : $cleanPath;
            if (file_exists(public_path('storage/' . $relPath)) || file_exists(storage_path('app/public/' . $relPath))) {
                return asset('storage/' . $relPath);
            }
            return asset('images/default-product.png');
        }

        // Match by product name or category keywords
        $name = strtolower($this->name ?? '');
        $cat = strtolower($this->category->name ?? $this->category->slug ?? '');

        if (str_contains($name, 'milo')) {
            return asset('images/products/milo.png');
        }
        if (str_contains($name, 'maggi') || str_contains($name, 'noodle')) {
            return asset('images/products/maggi.jpg');
        }
        if (str_contains($name, '100') || str_contains($name, 'plus')) {
            return asset('images/products/100plus.png');
        }
        if (str_contains($name, 'oreo') || str_contains($name, 'biscuit') || str_contains($name, 'cookie') || str_contains($cat, 'biscuit') || str_contains($cat, 'snack')) {
            return asset('images/products/oreo.png');
        }
        if (str_contains($name, 'coffee') || str_contains($name, 'nescafe')) {
            return asset('images/products/nescafe.jpg');
        }
        if (str_contains($name, 'tea')) {
            return asset('images/products/tea.jpg');
        }
        if (str_contains($name, 'oil') || str_contains($cat, 'oil')) {
            return asset('images/products/oil.png');
        }
        if (str_contains($name, 'rice') || str_contains($cat, 'rice') || str_contains($cat, 'grain')) {
            return asset('images/products/rice.png');
        }
        if (str_contains($name, 'milk') || str_contains($name, 'dairy') || str_contains($cat, 'dairy') || str_contains($cat, 'milk')) {
            return asset('images/products/milk.png');
        }
        if (str_contains($name, 'wash') || str_contains($name, 'detergent') || str_contains($name, 'laundry') || str_contains($cat, 'laundry')) {
            return asset('images/products/washing_powder.png');
        }
        if (str_contains($name, 'clean') || str_contains($name, 'bleach') || str_contains($name, 'dobi') || str_contains($name, 'toilet') || str_contains($cat, 'sanitation')) {
            return asset('images/products/cleaning.jpg');
        }
        if (str_contains($name, 'tissue') || str_contains($name, 'paper') || str_contains($cat, 'tissue') || str_contains($cat, 'paper')) {
            return asset('images/products/tissue.jpg');
        }
        if (str_contains($name, 'canned') || str_contains($name, 'sardine') || str_contains($name, 'tuna') || str_contains($cat, 'canned')) {
            return asset('images/products/canned.jpg');
        }
        if (str_contains($name, 'drink') || str_contains($name, 'juice') || str_contains($name, 'fanta') || str_contains($name, 'beverage') || str_contains($cat, 'beverage')) {
            return asset('images/products/bottle.png');
        }

        // Varied fallback pool across available high-res product images
        $pool = [
            'images/products/milo.png',
            'images/products/maggi.jpg',
            'images/products/100plus.png',
            'images/products/oreo.png',
            'images/products/nescafe.jpg',
            'images/products/bottle.png',
            'images/products/milk.png',
            'images/products/oil.png',
            'images/products/rice.png',
            'images/products/washing_powder.png',
            'images/products/canned.jpg',
            'images/products/cleaning.jpg',
            'images/products/coffee.jpg',
            'images/products/cookies.jpg',
            'images/products/laundry.jpg',
            'images/products/noodles.jpg',
            'images/products/tea.jpg',
            'images/products/tissue.jpg',
        ];

        return asset($pool[($this->id ?? 1) % count($pool)]);
    }

    public function getHasCustomImageAttribute(): bool
    {
        return !empty($this->featured_image);
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return $this->image_url;
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: ($this->name . ' (' . $this->pack_size . ') - ' . setting('site_name', 'Bulk Mart'));
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: ($this->short_description ?: 'Buy ' . $this->name . ' at unbeatable prices.');
    }
}

