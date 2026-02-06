@extends('layouts.app')

@section('title', __('Welcome'))

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-400 to-primary-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">{{ __('Welcome to ZARO') }}</h1>
            <p class="text-xl md:text-2xl mb-8 text-white/90">{{ __('Discover quality products for Kurdish children') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url(app()->getLocale() . '/products') }}" class="px-8 py-4 bg-white text-primary-600 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl">{{ __('Shop Now') }}</a>
                <a href="{{ url(app()->getLocale() . '/about') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300">{{ __('Learn More') }}</a>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
        </svg>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">{{ __('Why Choose ZARO?') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('Quality Products') }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ __('Carefully selected products for Kurdish children with high quality standards') }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('Fast Delivery') }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ __('Quick and reliable shipping to bring joy to your children faster') }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('24/7 Support') }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ __('Always here to help you with any questions or concerns') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6 text-gray-900 dark:text-white">{{ __('Ready to Start Shopping?') }}</h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">{{ __('Explore our collection and find the perfect products for your children') }}</p>
        <a href="{{ url(app()->getLocale() . '/products') }}" class="inline-block px-8 py-4 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl">{{ __('Browse Products') }}</a>
    </div>
</section>
@endsection
