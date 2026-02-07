<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentServiceInterface
{
    /**
     * Initialize payment and return checkout URL
     */
    public function createPayment(Order $order): array;

    /**
     * Verify payment callback/webhook
     */
    public function verifyPayment(array $data): array;

    /**
     * Refund payment
     */
    public function refundPayment(Order $order, float $amount): array;

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $transactionId): array;
}