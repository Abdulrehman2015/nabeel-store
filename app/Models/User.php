<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'is_active',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCashier(): bool
    {
        return in_array($this->role, ['cashier', 'staff']);
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'staff', 'cashier']);
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function canManagePos(): bool
    {
        return in_array($this->role, ['admin', 'staff', 'cashier']);
    }

    public function canAddProducts(): bool
    {
        return in_array($this->role, ['admin', 'staff', 'cashier']);
    }

    public function canEditProducts(): bool
    {
        return in_array($this->role, ['admin', 'staff', 'cashier']);
    }

    public function canDeleteProducts(): bool
    {
        return $this->isAdmin();
    }

    public function canViewReports(): bool
    {
        return $this->isAdmin();
    }

    public function canViewFinancials(): bool
    {
        return $this->isAdmin();
    }

    public function canManageSettings(): bool
    {
        return $this->isAdmin();
    }

    public function canManageStaff(): bool
    {
        return $this->isAdmin();
    }

    public function canResetData(): bool
    {
        return $this->isAdmin();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function cashierOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'cashier_id')->latest();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
