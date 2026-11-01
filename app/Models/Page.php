<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_published',
        'show_in_footer',
        'show_at_checkout',
        'sort_order',
        'current_version',
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
        'is_published' => 'boolean',
        'show_in_footer' => 'boolean',
        'show_at_checkout' => 'boolean',
        'sort_order' => 'integer',
        'current_version' => 'integer',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class)->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }

    public function scopeFooter($query)
    {
        return $query->where('is_published', true)->where('show_in_footer', true)->orderBy('sort_order');
    }

    /**
     * Replace dynamic placeholders with live settings.
     */
    public function getParsedContentAttribute(): string
    {
        $placeholders = [
            '{store_name}' => setting('site_name', 'Bulk Mart'),
            '{store_email}' => setting('store_email', setting('admin_email', 'support@bulkmart.my')),
            '{admin_email}' => setting('admin_email', setting('store_email', 'support@bulkmart.my')),
            '{store_phone}' => setting('store_phone', setting('admin_phone', '+60 12-345 6789')),
            '{admin_phone}' => setting('admin_phone', setting('store_phone', '+60 12-345 6789')),
            '{store_address}' => setting('store_address', '123 Bulk Wholesale Way, 50000 Kuala Lumpur, Malaysia'),
            '{company_registration_no}' => setting('company_registration_no', '202601009988 (123456-X)'),
            '{free_delivery_radius}' => setting('free_delivery_radius', 5) . ' km',
            '{paid_delivery_radius}' => setting('paid_delivery_radius', 2) . ' km',
            '{free_floor_limit}' => 'Floor ' . setting('free_floor_limit', 1),
            '{extra_floor_charge}' => format_currency(setting('extra_floor_charge', 10)),
            '{advance_percentage}' => setting('advance_percentage', 10) . '%',
            '{min_order_value}' => format_currency(setting('min_order_value', 100)),
            '{order_cutoff_time}' => setting('order_cutoff_time', '5:00 PM'),
            '{return_window_days}' => setting('return_window_days', '7') . ' days',
            '{current_year}' => date('Y'),
            '{last_updated}' => $this->updated_at ? $this->updated_at->format('F d, Y') : date('F d, Y'),
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $this->content ?? '');
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: ($this->title . ' - ' . setting('site_name', 'Bulk Mart'));
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: strip_tags(substr($this->content, 0, 160));
    }
}
