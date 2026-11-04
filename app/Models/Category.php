<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'sort_order',
        'is_active',
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

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function presetIcons(): array
    {
        return [
            'drink' => [
                'name' => 'Beverages & Soft Drinks',
                'gradient' => 'from-sky-500 to-blue-600',
                'shadow' => 'shadow-sky-500/25',
                'style_gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
                'style_shadow' => 'rgba(14, 165, 233, 0.4)',
                'color' => '#0284c7',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 2h10v3H7z"/><path d="M6 5h12l-1 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 5z"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>',
            ],
            'milk' => [
                'name' => 'Milk & Dairy Drinks',
                'gradient' => 'from-blue-500 to-indigo-600',
                'shadow' => 'shadow-blue-500/25',
                'style_gradient' => 'linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%)',
                'style_shadow' => 'rgba(59, 130, 246, 0.4)',
                'color' => '#3b82f6',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2h8v4H8z"/><path d="M7 6l-1 3h12l-1-3"/><path d="M6 9v11a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V9"/><path d="M10 14h4v4h-4z"/></svg>',
            ],
            'oil' => [
                'name' => 'Cooking Oil & Essentials',
                'gradient' => 'from-amber-500 to-orange-600',
                'shadow' => 'shadow-amber-500/25',
                'style_gradient' => 'linear-gradient(135deg, #f59e0b 0%, #ea580c 100%)',
                'style_shadow' => 'rgba(245, 158, 11, 0.4)',
                'color' => '#f59e0b',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2h6v3H9z"/><path d="M6 5h12v15a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5z"/><path d="M12 10a2.5 2.5 0 0 1 2.5 2.5c0 1.5-2.5 3.5-2.5 3.5s-2.5-2-2.5-3.5A2.5 2.5 0 0 1 12 10z"/><path d="M18 9h2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2"/></svg>',
            ],
            'noodle' => [
                'name' => 'Rice, Noodles & Grains',
                'gradient' => 'from-emerald-500 to-teal-600',
                'shadow' => 'shadow-emerald-500/25',
                'style_gradient' => 'linear-gradient(135deg, #10b981 0%, #0d9488 100%)',
                'style_shadow' => 'rgba(16, 185, 129, 0.4)',
                'color' => '#10b981',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11h16a8 8 0 0 1-8 8 8 8 0 0 1-8-8z"/><path d="M8 4c0 2 2 3 2 4"/><path d="M12 4c0 2 2 3 2 4"/><path d="M16 4c0 2 2 3 2 4"/><path d="M3 3l18 5"/></svg>',
            ],
            'can' => [
                'name' => 'Canned Food & Condiments',
                'gradient' => 'from-rose-500 to-red-600',
                'shadow' => 'shadow-rose-500/25',
                'style_gradient' => 'linear-gradient(135deg, #f43f5e 0%, #dc2626 100%)',
                'style_shadow' => 'rgba(244, 63, 94, 0.4)',
                'color' => '#f43f5e',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.66 3.58 3 8 3s8-1.34 8-3V5"/><path d="M4 12c0 1.66 3.58 3 8 3s8-1.34 8-3"/><path d="M10 5a2 2 0 1 0 4 0"/></svg>',
            ],
            'snack' => [
                'name' => 'Biscuits, Snacks & Crackers',
                'gradient' => 'from-purple-500 to-indigo-600',
                'shadow' => 'shadow-purple-500/25',
                'style_gradient' => 'linear-gradient(135deg, #a855f7 0%, #6366f1 100%)',
                'style_shadow' => 'rgba(168, 85, 247, 0.4)',
                'color' => '#a855f7',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="9" cy="9" r="1.5" fill="currentColor"/><circle cx="15" cy="9" r="1.5" fill="currentColor"/><circle cx="9" cy="15" r="1.5" fill="currentColor"/><circle cx="15" cy="15" r="1.5" fill="currentColor"/><circle cx="12" cy="12" r="1.5" fill="currentColor"/></svg>',
            ],
            'coffee' => [
                'name' => 'Coffee, Tea & Cocoa',
                'gradient' => 'from-amber-700 to-stone-800',
                'shadow' => 'shadow-amber-800/25',
                'style_gradient' => 'linear-gradient(135deg, #b45309 0%, #44403c 100%)',
                'style_shadow' => 'rgba(180, 83, 9, 0.4)',
                'color' => '#b45309',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><path d="M6 2v3"/><path d="M10 2v3"/><path d="M14 2v3"/></svg>',
            ],
            'laundry' => [
                'name' => 'Laundry & Fabric Care',
                'gradient' => 'from-cyan-500 to-teal-600',
                'shadow' => 'shadow-cyan-500/25',
                'style_gradient' => 'linear-gradient(135deg, #06b6d4 0%, #0891b2 100%)',
                'style_shadow' => 'rgba(6, 182, 212, 0.4)',
                'color' => '#06b6d4',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 2h10v4H7z"/><path d="M5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/><circle cx="12" cy="14" r="4"/><path d="M10 13c.5-1 1.5-1 2 0s1.5 1 2 0"/></svg>',
            ],
            'paper' => [
                'name' => 'Household Paper & Tissues',
                'gradient' => 'from-slate-600 to-slate-800',
                'shadow' => 'shadow-slate-600/25',
                'style_gradient' => 'linear-gradient(135deg, #64748b 0%, #334155 100%)',
                'style_shadow' => 'rgba(100, 116, 139, 0.4)',
                'color' => '#64748b',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="7" cy="8" rx="4" ry="6"/><ellipse cx="7" cy="8" rx="1.5" ry="2.5"/><path d="M7 2h10c2.2 0 4 2.7 4 6v8c0 3.3-1.8 6-4 6H7"/><path d="M7 14h10"/><path d="M7 20h10"/></svg>',
            ],
            'cleaning' => [
                'name' => 'Sanitation & Disinfectants',
                'gradient' => 'from-teal-600 to-emerald-700',
                'shadow' => 'shadow-teal-600/25',
                'style_gradient' => 'linear-gradient(135deg, #059669 0%, #047857 100%)',
                'style_shadow' => 'rgba(5, 150, 105, 0.4)',
                'color' => '#059669',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l7 4v6c0 5-3.5 9.5-7 11-3.5-1.5-7-6-7-11V6l7-4z"/><path d="M9 12l2 2 4-4"/></svg>',
            ],
            'bakery' => [
                'name' => 'Bakery & Bread',
                'gradient' => 'from-orange-500 to-amber-600',
                'shadow' => 'shadow-orange-500/25',
                'style_gradient' => 'linear-gradient(135deg, #f97316 0%, #d97706 100%)',
                'style_shadow' => 'rgba(249, 115, 22, 0.4)',
                'color' => '#f97316',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 13c0-3 3-5 9-5s9 2 9 5c0 4-2 7-9 7s-9-3-9-7z"/><path d="M8 8l2-4"/><path d="M12 8V4"/><path d="M16 8l-2-4"/></svg>',
            ],
            'frozen' => [
                'name' => 'Frozen & Chilled',
                'gradient' => 'from-sky-400 to-indigo-500',
                'shadow' => 'shadow-sky-400/25',
                'style_gradient' => 'linear-gradient(135deg, #38bdf8 0%, #6366f1 100%)',
                'style_shadow' => 'rgba(56, 189, 248, 0.4)',
                'color' => '#38bdf8',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M2 12h20"/><path d="M5 5l14 14"/><path d="M19 5L5 19"/><circle cx="12" cy="12" r="2"/></svg>',
            ],
            'meat' => [
                'name' => 'Meat & Poultry',
                'gradient' => 'from-red-600 to-rose-700',
                'shadow' => 'shadow-red-600/25',
                'style_gradient' => 'linear-gradient(135deg, #dc2626 0%, #be123c 100%)',
                'style_shadow' => 'rgba(220, 38, 38, 0.4)',
                'color' => '#dc2626',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 4a4 4 0 0 0-5.66 0l-9.9 9.9a4 4 0 0 0 5.66 5.66l9.9-9.9A4 4 0 0 0 19 4z"/><circle cx="15.5" cy="8.5" r="1.5"/></svg>',
            ],
            'baby' => [
                'name' => 'Baby & Infant Care',
                'gradient' => 'from-pink-500 to-rose-500',
                'shadow' => 'shadow-pink-500/25',
                'style_gradient' => 'linear-gradient(135deg, #ec4899 0%, #f43f5e 100%)',
                'style_shadow' => 'rgba(236, 72, 153, 0.4)',
                'color' => '#ec4899',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2h6v3H9z"/><path d="M8 5h8l1 3H7l1-3z"/><rect x="6" y="8" width="12" height="13" rx="2"/><path d="M10 12h4"/><path d="M10 15h4"/></svg>',
            ],
            'box' => [
                'name' => 'Bulk Carton / Box',
                'gradient' => 'from-amber-600 to-yellow-700',
                'shadow' => 'shadow-amber-600/25',
                'style_gradient' => 'linear-gradient(135deg, #d97706 0%, #a16207 100%)',
                'style_shadow' => 'rgba(217, 119, 6, 0.4)',
                'color' => '#d97706',
                'svg' => '<svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-4-9 4 9 4 9-4z"/><path d="M3 8v8l9 4 9-4V8"/><path d="M12 12v8"/></svg>',
            ],
        ];
    }

    public function getIconGradientClassAttribute(): string
    {
        $key = strtolower(trim($this->icon ?? 'box'));
        $presets = self::presetIcons();
        return $presets[$key]['gradient'] ?? 'from-lime-500 to-emerald-600';
    }

    public function getIconShadowClassAttribute(): string
    {
        $key = strtolower(trim($this->icon ?? 'box'));
        $presets = self::presetIcons();
        return $presets[$key]['shadow'] ?? 'shadow-lime-500/25';
    }

    public function getIconStyleGradientAttribute(): string
    {
        $key = strtolower(trim($this->icon ?? 'box'));
        $presets = self::presetIcons();
        return $presets[$key]['style_gradient'] ?? 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
    }

    public function getIconShadowColorAttribute(): string
    {
        $key = strtolower(trim($this->icon ?? 'box'));
        $presets = self::presetIcons();
        return $presets[$key]['style_shadow'] ?? 'rgba(16, 185, 129, 0.4)';
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = $this->icon ?: $this->image;
        if ($path && (str_contains($path, '/') || str_contains($path, '.'))) {
            $raw = ltrim($path, '/');
            return (str_starts_with($raw, 'storage/') || str_starts_with($raw, 'images/')) ? asset($raw) : asset('storage/' . $raw);
        }

        if ($this->image && (str_contains($this->image, '/') || str_contains($this->image, '.'))) {
            $raw = ltrim($this->image, '/');
            return (str_starts_with($raw, 'storage/') || str_starts_with($raw, 'images/')) ? asset($raw) : asset('storage/' . $raw);
        }

        if (file_exists(public_path("images/categories/{$this->slug}.png"))) {
            return asset("images/categories/{$this->slug}.png");
        }

        return null;
    }

    public function renderIcon(string $sizeClass = 'w-8 h-8', ?string $color = null): string
    {
        // 1. Prefer real product image
        if ($url = $this->image_url) {
            $fitClass = str_contains($sizeClass, 'object-') ? '' : 'object-cover';
            return "<img src=\"{$url}\" alt=\"{$this->name}\" class=\"{$sizeClass} {$fitClass}\" loading=\"lazy\">";
        }

        $colorStyle = $color ? "color: {$color};" : "color: #ffffff;";
        $key = strtolower(trim($this->icon ?? ''));
        $presets = self::presetIcons();

        // 2. Preset vector icon
        if (!empty($key) && isset($presets[$key])) {
            return "<div class=\"{$sizeClass}\" style=\"{$colorStyle}\">" . $presets[$key]['svg'] . "</div>";
        }

        // 3. Emoji or custom character
        if (!empty($this->icon) && mb_strlen($this->icon) <= 4) {
            return "<span style=\"font-size: 1.75rem; line-height: 1;\">{$this->icon}</span>";
        }

        // 4. Default carton box icon
        return "<div class=\"{$sizeClass}\" style=\"{$colorStyle}\">" . $presets['box']['svg'] . "</div>";
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: ($this->name . ' - ' . setting('site_name', 'Bulk Mart'));
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: ($this->description ?: 'Shop ' . $this->name . ' at great mart prices.');
    }
}
