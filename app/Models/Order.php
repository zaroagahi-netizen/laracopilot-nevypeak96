<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'session_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'subtotal',
        'shipping_cost',
        'tax',
        'discount',
        'coupon_code',
        'coupon_discount',
        'total',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'paid_at',
        'shipping_company',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'status',
        'customer_notes',
        'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function promotionUsages()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => __('Pending'),
            'processing' => __('Processing'),
            'shipped' => __('Shipped'),
            'delivered' => __('Delivered'),
            'cancelled' => __('Cancelled'),
            default => $this->status,
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => __('Pending'),
            'paid' => __('Paid'),
            'failed' => __('Failed'),
            'refunded' => __('Refunded'),
            default => $this->payment_status,
        };
    }

    public function getShippingCompanyNameAttribute()
    {
        return match($this->shipping_company) {
            'yurtici' => 'Yurtiçi Kargo',
            'aras' => 'Aras Kargo',
            'mng' => 'MNG Kargo',
            'ptt' => 'PTT Kargo',
            'ups' => 'UPS',
            'dhl' => 'DHL',
            'other' => __('Other'),
            default => $this->shipping_company,
        };
    }

    public function getTrackingUrlAttribute()
    {
        if (!$this->tracking_number) {
            return null;
        }

        return match($this->shipping_company) {
            'yurtici' => "https://www.yurticikargo.com/tr/online-servisler/gonderi-sorgula?code={$this->tracking_number}",
            'aras' => "https://www.araskargo.com.tr/kargo-takip/?tracking_number={$this->tracking_number}",
            'mng' => "https://www.mngkargo.com.tr/kargotakip?k={$this->tracking_number}",
            'ptt' => "https://gonderitakip.ptt.gov.tr/Track/Verify?q={$this->tracking_number}",
            'ups' => "https://www.ups.com/track?tracknum={$this->tracking_number}",
            'dhl' => "https://www.dhl.com/tr-tr/home/tracking.html?tracking-id={$this->tracking_number}",
            default => null,
        };
    }

    public function getHasPhysicalItemsAttribute()
    {
        return $this->items()->where('product_type', 'physical')->exists();
    }

    public function getHasDigitalItemsAttribute()
    {
        return $this->items()->where('product_type', 'digital')->exists();
    }

    public function getTotalDiscountAttribute()
    {
        return $this->discount + $this->coupon_discount;
    }

    // Methods
    public function markAsPaid($transactionId = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);
    }

    public function markAsShipped($shippingCompany, $trackingNumber)
    {
        $this->update([
            'status' => 'shipped',
            'shipping_company' => $shippingCompany,
            'tracking_number' => $trackingNumber,
            'shipped_at' => now(),
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'admin_notes' => $reason ? "Cancelled: {$reason}" : 'Cancelled',
        ]);

        // Decrement promotion usage if coupon was used
        if ($this->coupon_code) {
            $promotion = Promotion::byCode($this->coupon_code)->first();
            if ($promotion) {
                $promotion->decrementUsage();
            }
        }
    }

    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ZR' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}