<?php

namespace App\Services\Shipping;

use App\Models\Order;

interface ShippingServiceInterface
{
    /**
     * Calculate shipping cost
     */
    public function calculateShippingCost(Order $order): float;

    /**
     * Create shipment and get tracking number
     */
    public function createShipment(Order $order): array;

    /**
     * Get tracking information
     */
    public function getTrackingInfo(string $trackingNumber): array;

    /**
     * Get tracking URL
     */
    public function getTrackingUrl(string $trackingNumber): string;

    /**
     * Get carrier name
     */
    public function getCarrierName(): string;
}