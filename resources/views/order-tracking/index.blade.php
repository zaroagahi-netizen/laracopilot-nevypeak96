@extends('layouts.app')

@section('title', __('Track Your Order'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <div class="text-6xl mb-4">📦</div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Track Your Order') }}</h1>
        <p class="text-gray-600">{{ __('Enter your order details to check status') }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center gap-2 text-red-800">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <form action="{{ route('order-tracking.track') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    {{ __('Order Number') }}
                </label>
                <input 
                    type="text" 
                    name="order_number"
                    value="{{ old('order_number') }}"
                    placeholder="ZR20240122ABCDEF"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:outline-none transition-colors text-lg font-mono">
                @error('order_number')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-2">{{ __('You can find this in your order confirmation email') }}</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    {{ __('Email or Phone Number') }}
                </label>
                <input 
                    type="text" 
                    name="verification"
                    value="{{ old('verification') }}"
                    placeholder="{{ __('example@email.com or +90 555 123 4567') }}"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:outline-none transition-colors text-lg">
                @error('verification')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-2">{{ __('Enter the email or phone number you used for the order') }}</p>
            </div>

            <button 
                type="submit"
                class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                {{ __('Track Order') }}
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="font-semibold text-gray-900 mb-4">{{ __('Need Help?') }}</h3>
            <div class="space-y-2 text-sm text-gray-600">
                <p>• {{ __('Order number is in your confirmation email') }}</p>
                <p>• {{ __('Use the same email/phone you provided during checkout') }}</p>
                <p>• {{ __('For assistance, contact us via WhatsApp or email') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
