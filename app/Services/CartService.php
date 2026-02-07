<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CartService
{
    protected $cart;

    public function __construct()
    {
        $this->cart = $this->getOrCreateCart();
    }

    protected function getOrCreateCart()
    {
        $sessionId = session()->getId();
        
        if (!$sessionId) {
            session()->start();
            $sessionId = session()->getId();
        }
        
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
            ]);
        }
        
        // Release expired reservations
        $cart->releaseExpiredReservations();
        
        return $cart->fresh(['items.product']);
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function addProduct(Product $product, $quantity = 1)
    {
        $lockKey = "cart_lock_{$this->cart->id}_product_{$product->id}";
        
        return Cache::lock($lockKey, 10)->get(function () use ($product, $quantity) {
            if (!$product->active) {
                return ['success' => false, 'message' => __('Product is not available')];
            }
            
            if (!$product->hasAvailableStock($quantity)) {
                return ['success' => false, 'message' => __('Insufficient stock')];
            }
            
            $item = $this->cart->addItem($product, $quantity);
            
            if ($item) {
                $this->cart->refresh();
                return ['success' => true, 'message' => __('Product added to cart')];
            }
            
            return ['success' => false, 'message' => __('Failed to add product')];
        });
    }

    public function updateQuantity($itemId, $quantity)
    {
        $item = $this->cart->items()->find($itemId);
        
        if (!$item) {
            return ['success' => false, 'message' => __('Item not found')];
        }
        
        $lockKey = "cart_lock_{$this->cart->id}_item_{$itemId}";
        
        return Cache::lock($lockKey, 10)->get(function () use ($item, $quantity) {
            if ($quantity <= 0) {
                $item->delete();
                $this->cart->refresh();
                return ['success' => true, 'message' => __('Item removed from cart')];
            }
            
            if (!$item->product->hasAvailableStock($quantity)) {
                return ['success' => false, 'message' => __('Insufficient stock')];
            }
            
            $item->update([
                'quantity' => $quantity,
                'reserved_until' => now()->addMinutes(15),
            ]);
            
            $this->cart->refresh();
            return ['success' => true, 'message' => __('Cart updated')];
        });
    }

    public function removeItem($itemId)
    {
        $item = $this->cart->items()->find($itemId);
        
        if (!$item) {
            return ['success' => false, 'message' => __('Item not found')];
        }
        
        $item->delete();
        $this->cart->refresh();
        
        return ['success' => true, 'message' => __('Item removed from cart')];
    }

    public function clear()
    {
        $this->cart->clear();
        $this->cart->refresh();
        
        return ['success' => true, 'message' => __('Cart cleared')];
    }

    public function getItemCount()
    {
        return $this->cart->total_items;
    }

    public function getSubtotal()
    {
        return $this->cart->subtotal;
    }
}