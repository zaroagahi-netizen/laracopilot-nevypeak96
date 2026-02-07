<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_type',
        'digital_file_path',
        'quantity',
        'unit_price',
        'subtotal',
        'product_snapshot',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'product_snapshot' => 'array',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getIsDigitalAttribute()
    {
        return $this->product_type === 'digital';
    }

    public function getIsPhysicalAttribute()
    {
        return $this->product_type === 'physical';
    }
}