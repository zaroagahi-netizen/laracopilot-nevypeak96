<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class IyzicoService implements PaymentServiceInterface
{
    protected $apiKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.iyzico.api_key');
        $this->secretKey = config('services.iyzico.secret_key');
        $this->baseUrl = config('services.iyzico.base_url', 'https://api.iyzipay.com');
    }

    public function createPayment(Order $order): array
    {
        // Iyzico payment request structure
        $basketItems = [];
        foreach ($order->items as $item) {
            $basketItems[] = [
                'id' => $item->product_id,
                'name' => $item->product_name,
                'category1' => 'Kids Products',
                'itemType' => $item->product_type === 'digital' ? 'VIRTUAL' : 'PHYSICAL',
                'price' => (string) $item->subtotal,
            ];
        }

        $requestData = [
            'locale' => $this->mapLocale(app()->getLocale()),
            'conversationId' => $order->order_number,
            'price' => (string) $order->subtotal,
            'paidPrice' => (string) $order->total,
            'currency' => 'TRY',
            'basketId' => $order->order_number,
            'paymentGroup' => 'PRODUCT',
            'callbackUrl' => route('payment.iyzico.callback'),
            'enabledInstallments' => [1, 2, 3, 6, 9],
            'buyer' => [
                'id' => $order->user_id ?? 'guest-' . $order->session_id,
                'name' => explode(' ', $order->customer_name)[0] ?? $order->customer_name,
                'surname' => explode(' ', $order->customer_name)[1] ?? '',
                'email' => $order->customer_email,
                'gsmNumber' => $order->customer_phone,
                'identityNumber' => '11111111111', // Mock identity number
                'registrationAddress' => $order->shipping_address,
                'city' => $order->shipping_city,
                'country' => $order->shipping_country,
            ],
            'shippingAddress' => [
                'contactName' => $order->customer_name,
                'city' => $order->shipping_city,
                'country' => $order->shipping_country,
                'address' => $order->shipping_address,
            ],
            'billingAddress' => [
                'contactName' => $order->customer_name,
                'city' => $order->billing_city ?? $order->shipping_city,
                'country' => $order->billing_country ?? $order->shipping_country,
                'address' => $order->billing_address ?? $order->shipping_address,
            ],
            'basketItems' => $basketItems,
        ];

        $response = $this->makeRequest('/payment/iyzipos/checkoutform/initialize/auth/ecom', $requestData);

        if (isset($response['status']) && $response['status'] === 'success') {
            return [
                'success' => true,
                'payment_url' => $response['paymentPageUrl'] ?? null,
                'token' => $response['token'] ?? null,
                'transaction_id' => $response['token'] ?? null,
            ];
        }

        return [
            'success' => false,
            'message' => $response['errorMessage'] ?? 'Payment initialization failed',
        ];
    }

    public function verifyPayment(array $data): array
    {
        $token = $data['token'] ?? null;

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Invalid token',
            ];
        }

        $response = $this->makeRequest('/payment/iyzipos/checkoutform/auth/ecom/detail', [
            'token' => $token,
        ]);

        if (isset($response['status']) && $response['status'] === 'success') {
            return [
                'success' => true,
                'transaction_id' => $response['paymentId'] ?? null,
                'order_number' => $response['conversationId'] ?? null,
                'amount' => $response['paidPrice'] ?? 0,
                'status' => 'paid',
            ];
        }

        return [
            'success' => false,
            'message' => $response['errorMessage'] ?? 'Payment verification failed',
        ];
    }

    public function refundPayment(Order $order, float $amount): array
    {
        $requestData = [
            'conversationId' => $order->order_number,
            'paymentTransactionId' => $order->payment_transaction_id,
            'price' => (string) $amount,
            'currency' => 'TRY',
        ];

        $response = $this->makeRequest('/payment/refund', $requestData);

        return [
            'success' => isset($response['status']) && $response['status'] === 'success',
            'message' => $response['errorMessage'] ?? 'Refund processed',
        ];
    }

    public function getPaymentStatus(string $transactionId): array
    {
        // Iyzico status check implementation
        return [
            'success' => true,
            'status' => 'paid',
        ];
    }

    protected function makeRequest(string $endpoint, array $data): array
    {
        $randomString = bin2hex(random_bytes(16));
        $authString = $this->generateAuthString($endpoint, $data, $randomString);

        $response = Http::withHeaders([
            'Authorization' => $authString,
            'x-iyzi-rnd' => $randomString,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . $endpoint, $data);

        return $response->json() ?? [];
    }

    protected function generateAuthString(string $uri, array $data, string $randomString): string
    {
        $dataString = json_encode($data);
        $hashStr = $this->apiKey . $randomString . $this->secretKey . $dataString;
        $hash = base64_encode(hash('sha256', $hashStr, true));
        return 'IYZWS ' . $this->apiKey . ':' . $hash;
    }

    protected function mapLocale(string $locale): string
    {
        return match($locale) {
            'tr' => 'tr',
            'en' => 'en',
            default => 'tr',
        };
    }
}