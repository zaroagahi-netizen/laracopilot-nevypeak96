<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class ShopierService implements PaymentServiceInterface
{
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.shopier.api_key');
        $this->apiSecret = config('services.shopier.api_secret');
        $this->baseUrl = config('services.shopier.base_url', 'https://www.shopier.com/ShowProduct/api_pay4.php');
    }

    public function createPayment(Order $order): array
    {
        // Shopier payment request structure
        $params = [
            'API_key' => $this->apiKey,
            'website_index' => 1, // Your website index from Shopier
            'platform_order_id' => $order->order_number,
            'product_name' => "Order #{$order->order_number}",
            'product_type' => 0, // 0 = others, 1 = downloadable
            'buyer_name' => $order->customer_name,
            'buyer_phone' => $order->customer_phone,
            'buyer_email' => $order->customer_email,
            'buyer_address' => $order->shipping_address,
            'total_order_value' => $order->total,
            'currency' => 'TL',
            'current_language' => app()->getLocale(),
            'modul_version' => 'laravel_v1.0',
            'callback_url' => route('payment.shopier.callback'),
        ];

        // Generate signature
        $signature = $this->generateSignature($params);
        $params['random_key'] = $signature;

        // Return payment URL
        return [
            'success' => true,
            'payment_url' => $this->baseUrl . '?' . http_build_query($params),
            'transaction_id' => $order->order_number,
        ];
    }

    public function verifyPayment(array $data): array
    {
        // Verify Shopier callback
        $expectedSignature = $this->generateCallbackSignature($data);
        
        if (!isset($data['random_key']) || $data['random_key'] !== $expectedSignature) {
            return [
                'success' => false,
                'message' => 'Invalid signature',
            ];
        }

        $status = $data['status'] ?? null;
        $orderNumber = $data['platform_order_id'] ?? null;

        return [
            'success' => $status === '1',
            'transaction_id' => $data['payment_id'] ?? null,
            'order_number' => $orderNumber,
            'amount' => $data['total_order_value'] ?? 0,
            'status' => $status === '1' ? 'paid' : 'failed',
        ];
    }

    public function refundPayment(Order $order, float $amount): array
    {
        // Shopier refund implementation
        // Note: Shopier may require manual refund process
        return [
            'success' => false,
            'message' => 'Shopier refunds must be processed manually from Shopier dashboard',
        ];
    }

    public function getPaymentStatus(string $transactionId): array
    {
        // Shopier doesn't provide direct status check API
        return [
            'success' => false,
            'message' => 'Status check not available for Shopier',
        ];
    }

    protected function generateSignature(array $params): string
    {
        $str = '';
        foreach ($params as $key => $value) {
            if ($key !== 'random_key') {
                $str .= $value;
            }
        }
        return base64_encode(hash_hmac('sha256', $str, $this->apiSecret, true));
    }

    protected function generateCallbackSignature(array $data): string
    {
        $str = $data['platform_order_id'] . $data['status'] . $data['total_order_value'];
        return base64_encode(hash_hmac('sha256', $str, $this->apiSecret, true));
    }
}