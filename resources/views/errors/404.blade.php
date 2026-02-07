<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ \App\Helpers\LocaleHelper::direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Page Not Found') }} - ZARO</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(\App\Helpers\LocaleHelper::isRtl())
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: {{ \App\Helpers\LocaleHelper::isRtl() ? "['Noto Sans Arabic', 'system-ui', 'sans-serif']" : "['Baloo 2', 'system-ui', 'sans-serif']" }},
                    },
                },
            },
        }
    </script>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 to-yellow-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl mx-auto text-center">
        <!-- Cute Illustration -->
        <div class="mb-8 float-animation">
            <div class="text-9xl">🍼</div>
        </div>
        
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <h2 class="text-3xl font-bold text-gray-800 mb-4">{{ __('Oops! This product hasn\'t been born yet!') }}</h2>
        <p class="text-xl text-gray-600 mb-8">{{ __('The page you\'re looking for doesn\'t exist or has been moved.') }}</p>
        
        <!-- Search Box -->
        <div class="mb-8">
            <form action="{{ url(app()->getLocale() . '/products') }}" method="GET" class="max-w-md mx-auto">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search"
                        placeholder="{{ __('Search for products...') }}"
                        class="w-full px-6 py-4 rounded-full border-2 border-orange-200 focus:border-orange-400 focus:outline-none text-lg">
                    <button 
                        type="submit"
                        class="absolute {{ \App\Helpers\LocaleHelper::isRtl() ? 'left-2' : 'right-2' }} top-1/2 -translate-y-1/2 bg-gradient-to-r from-orange-400 to-orange-500 text-white px-6 py-2 rounded-full hover:from-orange-500 hover:to-orange-600 transition-all">
                        {{ __('Search') }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url(app()->getLocale()) }}" 
               class="px-8 py-4 bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold rounded-full hover:from-orange-500 hover:to-orange-600 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    {{ __('Go to Homepage') }}
                </div>
            </a>
            
            <a href="{{ url(app()->getLocale() . '/products') }}" 
               class="px-8 py-4 bg-white text-orange-600 font-bold rounded-full border-2 border-orange-400 hover:bg-orange-50 transition-all">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    {{ __('Browse Products') }}
                </div>
            </a>
        </div>
        
        <!-- Cute Message -->
        <div class="mt-12 text-gray-500">
            <p class="text-sm">{{ __('Don\'t worry, we have plenty of other amazing products for your little ones!') }}</p>
        </div>
    </div>
</body>
</html>
