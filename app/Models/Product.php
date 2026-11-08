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
            if (str_starts_with($this->featured_image, 'http')) {
                return $this->featured_image;
            }
            return asset('storage/' . $this->featured_image);
        }
        return asset('images/default-product.png');
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

