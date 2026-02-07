<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'reserved_until',
    ];

    protected $casts = [
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'reserved_until' => 'datetime',
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getIsReservedAttribute()
    {
        return $this->reserved_until && $this->reserved_until->isFuture();
    }

    public function getRemainingReservationTimeAttribute()
    {
        if (!$this->is_reserved) {
            return 0;
        }
        return $this->reserved_until->diffInSeconds(now());
    }
}