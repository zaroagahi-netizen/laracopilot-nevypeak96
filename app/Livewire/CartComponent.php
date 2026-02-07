<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use App\Services\PromotionService;
use App\Models\Promotion;

class CartComponent extends Component
{
    public $cart;
    public $couponCode = '';
    public $appliedCoupon = null;
    public $couponDiscount = 0;
    public $couponMessage = '';
    public $couponError = false;

    protected $cartService;
    protected $promotionService;

    public function boot(CartService $cartService, PromotionService $promotionService)
    {
        $this->cartService = $cartService;
        $this->promotionService = $promotionService;
    }

    public function mount()
    {
        $this->loadCart();
        $this->checkSessionCoupon();
    }

    public function loadCart()
    {
        $this->cart = $this->cartService->getCart();
    }

    public function checkSessionCoupon()
    {
        $sessionCoupon = session('applied_coupon');
        if ($sessionCoupon) {
            $this->appliedCoupon = Promotion::byCode($sessionCoupon['code'])->first();
            $this->couponCode = $sessionCoupon['code'];
            $this->couponDiscount = $sessionCoupon['discount'];
        }
    }

    public function applyCoupon()
    {
        $this->couponMessage = '';
        $this->couponError = false;

        if (empty($this->couponCode)) {
            $this->couponError = true;
            $this->couponMessage = __('Please enter a coupon code');
            return;
        }

        $result = $this->promotionService->validateCoupon(
            $this->couponCode,
            $this->cart->subtotal
        );

        if (!$result['success']) {
            $this->couponError = true;
            $this->couponMessage = $result['message'];
            $this->appliedCoupon = null;
            $this->couponDiscount = 0;
            session()->forget('applied_coupon');
            return;
        }

        // Apply coupon
        $this->appliedCoupon = $result['promotion'];
        $this->couponDiscount = $result['discount'];
        $this->couponMessage = $result['message'];

        // Store in session for checkout
        session([
            'applied_coupon' => [
                'code' => $this->appliedCoupon->code,
                'discount' => $this->couponDiscount,
            ]
        ]);

        $this->dispatch('coupon-applied');
    }

    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        $this->couponDiscount = 0;
        $this->couponCode = '';
        $this->couponMessage = '';
        $this->couponError = false;
        session()->forget('applied_coupon');

        $this->dispatch('coupon-removed');
    }

    public function updateQuantity($itemId, $quantity)
    {
        $result = $this->cartService->updateQuantity($itemId, $quantity);
        
        if ($result['success']) {
            $this->loadCart();
            
            // Revalidate coupon if applied
            if ($this->appliedCoupon) {
                $validation = $this->promotionService->validateCoupon(
                    $this->appliedCoupon->code,
                    $this->cart->subtotal
                );

                if ($validation['success']) {
                    $this->couponDiscount = $validation['discount'];
                    session(['applied_coupon.discount' => $this->couponDiscount]);
                } else {
                    $this->removeCoupon();
                    $this->couponError = true;
                    $this->couponMessage = $validation['message'];
                }
            }
        }
    }

    public function removeItem($itemId)
    {
        $result = $this->cartService->removeItem($itemId);
        
        if ($result['success']) {
            $this->loadCart();
            
            // Revalidate coupon if applied
            if ($this->appliedCoupon) {
                $validation = $this->promotionService->validateCoupon(
                    $this->appliedCoupon->code,
                    $this->cart->subtotal
                );

                if ($validation['success']) {
                    $this->couponDiscount = $validation['discount'];
                    session(['applied_coupon.discount' => $this->couponDiscount]);
                } else {
                    $this->removeCoupon();
                }
            }
        }
    }

    public function getSubtotalProperty()
    {
        return $this->cart->subtotal ?? 0;
    }

    public function getTotalProperty()
    {
        return max(0, $this->subtotal - $this->couponDiscount);
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}