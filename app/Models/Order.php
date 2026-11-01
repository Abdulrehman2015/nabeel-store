<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'delivery_city',
        'floor',
        'landmark',
        'order_notes',
        'latitude',
        'longitude',
        'distance_km',
        'delivery_zone',
        'subtotal',
        'floor_charge',
        'delivery_charge',
        'grand_total',
        'advance_percentage',
        'advance_amount',
        'balance_amount',
        'payment_method',
        'payment_reference',
        'payment_proof_path',
        'payment_status',
        'payment_rejection_reason',
        'payment_verified_at',
        'payment_verified_by',
        'order_status',
        'internal_notes',
        'accepted_policy_versions',
        'ip_address',
        'user_agent',
        'idempotency_token',
    ];

    protected $casts = [
        'floor' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'distance_km' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'floor_charge' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'advance_percentage' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'payment_verified_at' => 'datetime',
        'accepted_policy_versions' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class)->latest();
    }

    public function scopePendingVerification($query)
    {
        return $query->where('order_status', 'pending_payment_verification');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('order_status', 'confirmed');
    }

    /**
     * Generate human-friendly status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending_payment_verification' => 'Pending Verification',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'payment_rejected' => 'Payment Rejected',
            default => ucfirst(str_replace('_', ' ', $this->order_status)),
        };
    }

    /**
     * Badge CSS class for status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->order_status) {
            'pending_payment_verification' => 'bg-amber-100 text-amber-800 border-amber-300',
            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-300',
            'processing' => 'bg-purple-100 text-purple-800 border-purple-300',
            'out_for_delivery' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'cancelled' => 'bg-gray-100 text-gray-800 border-gray-300',
            'payment_rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    /**
     * Secure signed URL to view private payment proof screenshot.
     */
    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof_path) {
            return null;
        }

        return URL::temporarySignedRoute(
            'admin.orders.payment-proof',
            now()->addMinutes(60),
            ['order' => $this->id]
        );
    }
}
