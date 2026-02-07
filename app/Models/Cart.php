<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Methods
    public function addItem(Product $product, $quantity = 1)
    {
        $item = $this->items()->where('product_id', $product->id)->first();
        
        if ($item) {
            $newQuantity = $item->quantity + $quantity;
            if ($product->hasAvailableStock($newQuantity)) {
                $item->update([
                    'quantity' => $newQuantity,
                    'reserved_until' => now()->addMinutes(15),
                ]);
                return $item;
            }
            return false;
        }
        
        if ($product->hasAvailableStock($quantity)) {
            return $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'reserved_until' => now()->addMinutes(15),
            ]);
        }
        
        return false;
    }

    public function updateItem(CartItem $item, $quantity)
    {
        if ($quantity <= 0) {
            return $item->delete();
        }
        
        if ($item->product->hasAvailableStock($quantity)) {
            return $item->update([
                'quantity' => $quantity,
                'reserved_until' => now()->addMinutes(15),
            ]);
        }
        
        return false;
    }

    public function removeItem(CartItem $item)
    {
        return $item->delete();
    }

    public function clear()
    {
        return $this->items()->delete();
    }

    public function releaseExpiredReservations()
    {
        $this->items()->where('reserved_until', '<', now())->delete();
    }
}