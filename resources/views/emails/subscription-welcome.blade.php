<!DOCTYPE html>
<html lang="{{ $locale ?? 'tr' }}" dir="{{ \App\Helpers\LocaleHelper::direction($locale ?? 'tr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Welcome to ZARO!') }}</title>
    <style>
        body {
            font-family: {{ \App\Helpers\LocaleHelper::isRtl($locale ?? 'tr') ? "'Noto Sans Arabic', sans-serif" : "'Baloo 2', system-ui, sans-serif" }};
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
        .gift-box {
            background: #fef3c7;
            border: 2px dashed #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
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
            <h1 style="margin: 0; font-size: 36px;">🎁 {{ __('Welcome to ZARO!') }}</h1>
        </div>
        <div class="content">
            <p style="font-size: 18px; line-height: 1.6;">{{ __('Hello') }},</p>
            <p style="font-size: 16px; line-height: 1.6;">{{ __('Thank you for subscribing to our newsletter! We\'re excited to have you in our community.') }}</p>
            
            <div class="gift-box">
                <div style="font-size: 48px; margin-bottom: 10px;">📚</div>
                <h2 style="margin: 0 0 10px 0; color: #f59e0b;">{{ __('Your Free Kurdish PDF is Ready!') }}</h2>
                <p style="margin: 0; color: #92400e;">{{ __('Educational activities for Kurdish children') }}</p>
                <a href="#" class="button">{{ __('Download PDF') }}</a>
            </div>

            <p style="font-size: 16px; line-height: 1.6; margin-top: 30px;">
                {{ __('Stay tuned for exclusive offers, new products, and educational content for your little ones.') }}
            </p>

            <p style="font-size: 16px; line-height: 1.6;">
                {{ __('Have questions? Reply to this email anytime!') }}
            </p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} ZARO. {{ __('All rights reserved.') }}</p>
            <p>{{ __('Made with') }} ❤️ {{ __('for Kurdish children') }}</p>
        </div>
    </div>
</body>
</html>
