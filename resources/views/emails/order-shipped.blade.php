<!DOCTYPE html>
<html lang="{{ $locale ?? 'tr' }}" dir="{{ \App\Helpers\LocaleHelper::direction($locale ?? 'tr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Your Order Has Been Shipped!') }}</title>
    <style>
        body {
            font-family: {{ \App\Helpers\LocaleHelper::isRtl($locale ?? 'tr') ? "'Noto Sans Arabic', 'Noto Kufi Arabic', sans-serif" : "'Baloo 2', 'Itim', system-ui, sans-serif" }};
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
            direction: {{ \App\Helpers\LocaleHelper::direction($locale ?? 'tr') }};
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #f6b751 0%, #f18f1e 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .content {
            padding: 40px 30px;
            color: #374151;
        }
        .order-info {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .tracking-box {
            background: #e0f2fe;
            border: 2px solid #0ea5e9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .tracking-number {
            font-size: 24px;
            font-weight: bold;
            color: #0369a1;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            background: #f18f1e;
            color: white;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 600;
        }
        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 32px;">📦 {{ __('Your Order Has Been Shipped!') }}</h1>
        </div>
        <div class="content">
            <p style="font-size: 18px; line-height: 1.6;">{{ __('Hello') }} {{ $customer_name }},</p>
            <p style="font-size: 16px; line-height: 1.6;">{{ __('Great news! Your order has been shipped and is on its way to you.') }}</p>
            
            <div class="order-info">
                <p style="margin: 5px 0;"><strong>{{ __('Order Number:') }}</strong> {{ $order_number }}</p>
                <p style="margin: 5px 0;"><strong>{{ __('Shipping Company:') }}</strong> {{ $shipping_company }}</p>
                <p style="margin: 5px 0;"><strong>{{ __('Shipped At:') }}</strong> {{ $shipped_at }}</p>
            </div>

            @if($tracking_number)
            <div class="tracking-box">
                <p style="margin: 0 0 10px 0; font-size: 16px; font-weight: 600;">{{ __('Tracking Number:') }}</p>
                <div class="tracking-number">{{ $tracking_number }}</div>
                @if($tracking_url)
                <a href="{{ $tracking_url }}" class="button" target="_blank">{{ __('Track Your Order') }}</a>
                @endif
            </div>
            @endif

            <p style="font-size: 16px; line-height: 1.6; margin-top: 30px;">
                {{ __('You can track your order using the tracking number above. Your package should arrive within 2-5 business days.') }}
            </p>

            <p style="font-size: 16px; line-height: 1.6;">
                {{ __('If you have any questions, feel free to contact us.') }}
            </p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} ZARO. {{ __('All rights reserved.') }}</p>
            <p>{{ __('Made with') }} ❤️ {{ __('for Kurdish children') }}</p>
        </div>
    </div>
</body>
</html>
