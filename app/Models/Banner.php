<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'badge',
        'link',
        'button_text',
        'desktop_image',
        'mobile_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getDesktopImageUrlAttribute(): string
    {
        if (str_starts_with($this->desktop_image, 'http')) {
            return $this->desktop_image;
        }
        return asset('storage/' . $this->desktop_image);
    }

    public function getMobileImageUrlAttribute(): string
    {
        if ($this->mobile_image) {
            if (str_starts_with($this->mobile_image, 'http')) {
                return $this->mobile_image;
            }
            return asset('storage/' . $this->mobile_image);
        }
        return $this->desktop_image_url;
    }
}

