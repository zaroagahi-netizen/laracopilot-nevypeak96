<!DOCTYPE html>
<html lang="{{ $locale ?? 'tr' }}" dir="{{ \App\Helpers\LocaleHelper::direction($locale ?? 'tr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Welcome to ZARO') }}</title>
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
        .button {
            display: inline-block;
            background: #f18f1e;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
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
            <h1 style="margin: 0; font-size: 36px;">{{ __('Welcome to ZARO!') }}</h1>
        </div>
        <div class="content">
            <p style="font-size: 18px; line-height: 1.6;">{{ __('Hello') }} {{ $name ?? __('Friend') }},</p>
            <p style="font-size: 16px; line-height: 1.6;">{{ __('Thank you for joining ZARO! We are excited to have you as part of our community.') }}</p>
            <p style="font-size: 16px; line-height: 1.6;">{{ __('Discover our wide range of quality products for Kurdish children and enjoy exclusive offers.') }}</p>
            <div style="text-align: center;">
                <a href="{{ url(($locale ?? 'tr') . '/products') }}" class="button">{{ __('Start Shopping') }}</a>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} ZARO. {{ __('All rights reserved.') }}</p>
            <p>{{ __('Made with') }} ❤️ {{ __('for Kurdish children') }}</p>
        </div>
    </div>
</body>
</html>
