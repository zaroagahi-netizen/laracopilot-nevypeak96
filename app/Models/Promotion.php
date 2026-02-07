<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_cart_amount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_cart_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'active' => 'boolean',
    ];

    // Relationships
    public function usages()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeValid($query)
    {
        $now = now();
        return $query->where('active', true)
            ->where(function($q) use ($now) {
                $q->whereNull('valid_from')
                  ->orWhere('valid_from', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', $now);
            });
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper(trim($code)));
    }

    // Accessors
    public function getIsValidAttribute()
    {
        if (!$this->active) {
            return false;
        }

        $now = now();

        if ($this->valid_from && $this->valid_from->isFuture()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function getIsUsageLimitReachedAttribute()
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    public function getRemainingUsagesAttribute()
    {
        if (!$this->usage_limit) {
            return null;
        }
        return max(0, $this->usage_limit - $this->used_count);
    }

    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'percentage' => __('Percentage Discount'),
            'fixed' => __('Fixed Discount'),
            default => $this->type,
        };
    }

    // Mutators
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper(trim($value));
    }

    // Methods
    public function calculateDiscount($cartTotal)
    {
        if (!$this->is_valid) {
            return 0;
        }

        if ($cartTotal < $this->min_cart_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = ($cartTotal * $this->value) / 100;
            // Don't allow discount to exceed cart total
            return min($discount, $cartTotal);
        }

        // Fixed discount
        return min($this->value, $cartTotal);
    }

    public function canBeUsed($cartTotal = 0)
    {
        if (!$this->is_valid) {
            return [
                'valid' => false,
                'message' => __('This coupon is not valid'),
            ];
        }

        if ($this->is_expired) {
            return [
                'valid' => false,
                'message' => __('This coupon has expired'),
            ];
        }

        if ($this->is_usage_limit_reached) {
            return [
                'valid' => false,
                'message' => __('This coupon has reached its usage limit'),
            ];
        }

        if ($cartTotal > 0 && $cartTotal < $this->min_cart_amount) {
            return [
                'valid' => false,
                'message' => __('Minimum cart amount is :amount ₺', ['amount' => number_format($this->min_cart_amount, 2)]),
            ];
        }

        return [
            'valid' => true,
            'message' => __('Coupon applied successfully'),
        ];
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    public function decrementUsage()
    {
        if ($this->used_count > 0) {
            $this->decrement('used_count');
        }
    }
}