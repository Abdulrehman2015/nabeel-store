<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HowItWorksStep extends Model
{
    protected $fillable = [
        'step_number',
        'title',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'step_number' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
