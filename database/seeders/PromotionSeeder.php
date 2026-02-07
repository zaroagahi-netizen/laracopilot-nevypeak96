<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        $promotions = [
            [
                'code' => 'WELCOME10',
                'description' => 'Hoş geldin indirimi - İlk alışverişinizde %10 indirim',
                'type' => 'percentage',
                'value' => 10,
                'min_cart_amount' => 100,
                'usage_limit' => null,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'active' => true,
            ],
            [
                'code' => 'YILBASI25',
                'description' => 'Yılbaşı kampanyası - %25 indirim',
                'type' => 'percentage',
                'value' => 25,
                'min_cart_amount' => 200,
                'usage_limit' => 100,
                'valid_from' => now(),
                'valid_until' => now()->addDays(30),
                'active' => true,
            ],
            [
                'code' => 'FIRSTORDER',
                'description' => 'İlk siparişinizde 50₺ indirim',
                'type' => 'fixed',
                'value' => 50,
                'min_cart_amount' => 300,
                'usage_limit' => 500,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(6),
                'active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'description' => 'Ücretsiz kargo kuponu',
                'type' => 'fixed',
                'value' => 20,
                'min_cart_amount' => 150,
                'usage_limit' => null,
                'valid_from' => now(),
                'valid_until' => null,
                'active' => true,
            ],
            [
                'code' => 'SUMMER2024',
                'description' => 'Yaz kampanyası - %15 indirim',
                'type' => 'percentage',
                'value' => 15,
                'min_cart_amount' => 0,
                'usage_limit' => 1000,
                'valid_from' => now()->subDays(30),
                'valid_until' => now()->subDays(1), // Expired
                'active' => false,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}