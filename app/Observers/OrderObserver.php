<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\LocalizedMail;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Check if status changed to 'shipped'
        if ($order->isDirty('status') && $order->status === 'shipped') {
            $this->sendShippingNotification($order);
        }
    }

    /**
     * Send shipping notification email
     */
    protected function sendShippingNotification(Order $order)
    {
        if (!$order->customer_email) {
            return;
        }

        $locale = app()->getLocale();
        
        // Prepare email data
        $data = [
            'subject' => __('Your Order Has Been Shipped!'),
            'order' => $order,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'shipping_company' => $order->shipping_company_name,
            'tracking_number' => $order->tracking_number,
            'tracking_url' => $order->tracking_url,
            'shipped_at' => $order->shipped_at?->format('d/m/Y H:i'),
        ];

        // Queue email
        Mail::to($order->customer_email)
            ->queue(new LocalizedMail(
                locale: $locale,
                viewName: 'emails.order-shipped',
                data: $data
            ));
    }
}