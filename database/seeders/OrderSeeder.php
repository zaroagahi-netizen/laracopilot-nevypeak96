<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $products = Product::limit(5)->get();

        // Create 10 sample orders with different statuses
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        $shippingCompanies = ['yurtici', 'aras', 'mng', 'ptt'];

        for ($i = 0; $i < 10; $i++) {
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $status === 'cancelled' ? 'refunded' : $paymentStatuses[array_rand($paymentStatuses)];
            $shippingCompany = $shippingCompanies[array_rand($shippingCompanies)];

            $subtotal = 0;
            $orderItems = [];

            // Add 1-3 random products
            $itemCount = rand(1, 3);
            foreach ($products->random($itemCount) as $product) {
                $quantity = rand(1, 3);
                $itemSubtotal = $product->price * $quantity;
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_type' => $product->type,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $shippingCost = 20.00;
            $tax = $subtotal * 0.18;
            $total = $subtotal + $shippingCost + $tax;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user?->id,
                'customer_name' => fake()->name(),
                'customer_email' => fake()->email(),
                'customer_phone' => fake()->phoneNumber(),
                'shipping_address' => fake()->address(),
                'shipping_city' => fake()->city(),
                'shipping_postal_code' => fake()->postcode(),
                'shipping_country' => 'TR',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => ['shopier', 'iyzico', 'bank_transfer'][array_rand(['shopier', 'iyzico', 'bank_transfer'])],
                'payment_status' => $paymentStatus,
                'payment_transaction_id' => $paymentStatus === 'paid' ? 'TXN' . strtoupper(substr(uniqid(), -8)) : null,
                'paid_at' => $paymentStatus === 'paid' ? now()->subDays(rand(1, 10)) : null,
                'shipping_company' => in_array($status, ['shipped', 'delivered']) ? $shippingCompany : null,
                'tracking_number' => in_array($status, ['shipped', 'delivered']) ? strtoupper(substr($shippingCompany, 0, 2)) . strtoupper(substr(uniqid(), -10)) : null,
                'shipped_at' => in_array($status, ['shipped', 'delivered']) ? now()->subDays(rand(1, 5)) : null,
                'delivered_at' => $status === 'delivered' ? now()->subDays(rand(0, 2)) : null,
                'status' => $status,
                'customer_notes' => rand(0, 1) ? fake()->sentence() : null,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Create order items
            foreach ($orderItems as $itemData) {
                OrderItem::create(array_merge($itemData, [
                    'order_id' => $order->id,
                ]));
            }
        }
    }
}