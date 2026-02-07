<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ \App\Helpers\LocaleHelper::direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ZARO') }} - @yield('title', 'Zarokên Kurdan')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(\App\Helpers\LocaleHelper::isRtl())
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&family=Noto+Kufi+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Itim&display=swap" rel="stylesheet">
    @endif
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: {{ \App\Helpers\LocaleHelper::isRtl() ? "['Noto Sans Arabic', 'Noto Kufi Arabic', 'system-ui', 'sans-serif']" : "['Baloo 2', 'Itim', 'system-ui', 'sans-serif']" }},
                    },
                    colors: {
                        primary: {
                            50: '#fef3e2',
                            100: '#fce7c5',
                            200: '#f9cf8b',
                            300: '#f6b751',
                            400: '#f39f17',
                            500: '#f18f1e',
                            600: '#c17218',
                            700: '#915512',
                            800: '#60380c',
                            900: '#301c06',
                        },
                    },
                },
            },
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 antialiased {{ \App\Helpers\LocaleHelper::isRtl() ? 'rtl' : 'ltr' }}">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url(app()->getLocale()) }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">Z</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">ZARO</span>
                    </a>
                </div>
                
                <!-- Mobile Language Switcher (Always Visible) -->
                <x-mobile-language-switcher />
                
                <!-- Desktop Navigation & Language Switcher -->
                <div class="hidden md:flex items-center gap-6">
                    <nav class="flex items-center gap-6">
                        <a href="{{ url(app()->getLocale()) }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-500 transition-colors">{{ __('Home') }}</a>
                        <a href="{{ url(app()->getLocale() . '/products') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-500 transition-colors">{{ __('Products') }}</a>
                        <a href="{{ url(app()->getLocale() . '/about') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-500 transition-colors">{{ __('About') }}</a>
                        <a href="{{ url(app()->getLocale() . '/contact') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary-500 transition-colors">{{ __('Contact') }}</a>
                    </nav>
                    <x-language-switcher />
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Subscription Banner -->
    <x-subscription-banner />
    
    <!-- Birthday Popup (conditional) -->
    <x-birthday-popup />
    
    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">{{ __('ZARO') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('Quality products for Kurdish children') }}</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">{{ __('Quick Links') }}</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ url(app()->getLocale() . '/products') }}" class="hover:text-primary-400 transition-colors">{{ __('Products') }}</a></li>
                    <li><a href="{{ url(app()->getLocale() . '/about') }}" class="hover:text-primary-400 transition-colors">{{ __('About Us') }}</a></li>
                    <li><a href="{{ url(app()->getLocale() . '/contact') }}" class="hover:text-primary-400 transition-colors">{{ __('Contact') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">{{ __('Support') }}</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-primary-400 transition-colors">{{ __('FAQ') }}</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">{{ __('Shipping Info') }}</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">{{ __('Returns') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">{{ __('Newsletter') }}</h4>
                <p class="text-gray-400 text-sm mb-4">{{ __('Subscribe to get special offers') }}</p>
                <form class="flex gap-2">
                    <input type="email" placeholder="{{ __('Your email') }}" class="flex-1 px-3 py-2 rounded bg-gray-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 rounded text-sm font-medium transition-colors">{{ __('Subscribe') }}</button>
                </form>
            </div>
        </div>
        <div class="border-t border-gray-700 py-6 text-center text-sm text-gray-400">
            <p>© {{ date('Y') }} ZARO. {{ __('All rights reserved.') }}</p>
            <p class="mt-2">{{ __('Made with') }} ❤️ {{ __('by') }} <a href="https://laracopilot.com/" target="_blank" class="hover:text-primary-400 transition-colors">LaraCopilot</a></p>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
