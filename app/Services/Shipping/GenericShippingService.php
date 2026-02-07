<?php

namespace App\Services\Shipping;

use App\Models\Order;

class GenericShippingService implements ShippingServiceInterface
{
    protected $carrier;

    public function __construct(string $carrier = 'yurtici')
    {
        $this->carrier = $carrier;
    }

    public function calculateShippingCost(Order $order): float
    {
        // Generic shipping cost calculation
        // In production, this would call carrier API
        
        if (!$order->has_physical_items) {
            return 0.00;
        }

        // Simple weight-based calculation (mock)
        $itemCount = $order->items()->where('product_type', 'physical')->sum('quantity');
        
        return match($this->carrier) {
            'yurtici' => $itemCount * 15.00,
            'aras' => $itemCount * 18.00,
            'mng' => $itemCount * 16.00,
            'ptt' => $itemCount * 12.00,
            'ups' => $itemCount * 45.00,
            'dhl' => $itemCount * 50.00,
            default => 20.00,
        };
    }

    public function createShipment(Order $order): array
    {
        // In production, this would call carrier API to create shipment
        // For now, return mock tracking number
        
        $trackingNumber = $this->generateMockTrackingNumber();

        return [
            'success' => true,
            'tracking_number' => $trackingNumber,
            'carrier' => $this->carrier,
            'estimated_delivery' => now()->addDays(3)->format('Y-m-d'),
        ];
    }

    public function getTrackingInfo(string $trackingNumber): array
    {
        // In production, this would call carrier API
        return [
            'tracking_number' => $trackingNumber,
            'carrier' => $this->carrier,
            'status' => 'in_transit',
            'estimated_delivery' => now()->addDays(2)->format('Y-m-d'),
            'events' => [
                ['date' => now()->subDays(1)->format('Y-m-d H:i'), 'status' => 'Picked up'],
                ['date' => now()->format('Y-m-d H:i'), 'status' => 'In transit'],
            ],
        ];
    }

    public function getTrackingUrl(string $trackingNumber): string
    {
        return match($this->carrier) {
            'yurtici' => "https://www.yurticikargo.com/tr/online-servisler/gonderi-sorgula?code={$trackingNumber}",
            'aras' => "https://www.araskargo.com.tr/kargo-takip/?tracking_number={$trackingNumber}",
            'mng' => "https://www.mngkargo.com.tr/kargotakip?k={$trackingNumber}",
            'ptt' => "https://gonderitakip.ptt.gov.tr/Track/Verify?q={$trackingNumber}",
            'ups' => "https://www.ups.com/track?tracknum={$trackingNumber}",
            'dhl' => "https://www.dhl.com/tr-tr/home/tracking.html?tracking-id={$trackingNumber}",
            default => '#',
        };
    }

    public function getCarrierName(): string
    {
        return match($this->carrier) {
            'yurtici' => 'Yurtiçi Kargo',
            'aras' => 'Aras Kargo',
            'mng' => 'MNG Kargo',
            'ptt' => 'PTT Kargo',
            'ups' => 'UPS',
            'dhl' => 'DHL',
            default => ucfirst($this->carrier),
        };
    }

    protected function generateMockTrackingNumber(): string
    {
        $prefix = match($this->carrier) {
            'yurtici' => 'YK',
            'aras' => 'AR',
            'mng' => 'MN',
            'ptt' => 'PT',
            'ups' => '1Z',
            'dhl' => 'DH',
            default => 'TR',
        };

        return $prefix . strtoupper(substr(uniqid(), -10));
    }
}