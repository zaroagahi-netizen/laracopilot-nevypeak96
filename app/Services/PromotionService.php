<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\PromotionUsage;
use App\Models\Order;

class PromotionService
{
    /**
     * Validate and apply coupon code
     */
    public function validateCoupon($code, $cartTotal)
    {
        $code = strtoupper(trim($code));

        if (empty($code)) {
            return [
                'success' => false,
                'message' => __('Please enter a coupon code'),
            ];
        }

        $promotion = Promotion::byCode($code)->first();

        if (!$promotion) {
            return [
                'success' => false,
                'message' => __('Invalid coupon code'),
            ];
        }

        $validation = $promotion->canBeUsed($cartTotal);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
            ];
        }

        $discount = $promotion->calculateDiscount($cartTotal);

        return [
            'success' => true,
            'message' => $validation['message'],
            'promotion' => $promotion,
            'discount' => $discount,
        ];
    }

    /**
     * Apply promotion to order
     */
    public function applyPromotionToOrder(Order $order, Promotion $promotion, $discountAmount)
    {
        // Update order with coupon details
        $order->update([
            'coupon_code' => $promotion->code,
            'coupon_discount' => $discountAmount,
            'total' => $order->subtotal + $order->shipping_cost + $order->tax - $order->discount - $discountAmount,
        ]);

        // Record promotion usage
        PromotionUsage::create([
            'promotion_id' => $promotion->id,
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'session_id' => $order->session_id,
            'discount_amount' => $discountAmount,
        ]);

        // Increment promotion usage count
        $promotion->incrementUsage();
    }

    /**
     * Remove promotion from order (if order is cancelled)
     */
    public function removePromotionFromOrder(Order $order)
    {
        if (!$order->coupon_code) {
            return;
        }

        $promotion = Promotion::byCode($order->coupon_code)->first();

        if ($promotion) {
            $promotion->decrementUsage();
        }

        // Delete promotion usage record
        PromotionUsage::where('order_id', $order->id)->delete();

        // Update order
        $order->update([
            'coupon_code' => null,
            'coupon_discount' => 0,
            'total' => $order->subtotal + $order->shipping_cost + $order->tax - $order->discount,
        ]);
    }

    /**
     * Get active promotions for display
     */
    public function getActivePromotions()
    {
        return Promotion::valid()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}