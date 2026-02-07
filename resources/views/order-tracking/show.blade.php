@extends('layouts.app')

@section('title', __('Order Status') . ' - ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Order Found Success -->
    <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-green-900">{{ __('Order Found!') }}</h2>
                <p class="text-green-700">{{ __('Order Number:') }} <span class="font-mono font-bold">{{ $order->order_number }}</span></p>
            </div>
        </div>
    </div>

    <!-- Order Status Timeline -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Order Status') }}</h3>
        
        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
            
            <!-- Status Steps -->
            <div class="space-y-8 relative">
                <!-- Pending -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center z-10
                        {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }}">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">{{ __('Order Received') }}</h4>
                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Processing -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center z-10
                        {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : ($order->status === 'pending' ? 'bg-blue-500 animate-pulse' : 'bg-gray-300') }}">
                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">{{ __('Processing') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Your order is being prepared') }}</p>
                    </div>
                </div>

                <!-- Shipped -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center z-10
                        {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : ($order->status === 'processing' ? 'bg-blue-500 animate-pulse' : 'bg-gray-300') }}">
                        @if(in_array($order->status, ['shipped', 'delivered']))
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">{{ __('Shipped') }}</h4>
                        @if($order->status === 'shipped' || $order->status === 'delivered')
                            <p class="text-sm text-gray-600">{{ $order->shipped_at?->format('d/m/Y H:i') }}</p>
                            @if($order->shipping_company)
                                <p class="text-sm text-gray-600 mt-1">{{ __('Carrier:') }} {{ $order->shipping_company_name }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-600">{{ __('Waiting for shipment') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Delivered -->
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center z-10
                        {{ $order->status === 'delivered' ? 'bg-green-500' : ($order->status === 'shipped' ? 'bg-blue-500 animate-pulse' : 'bg-gray-300') }}">
                        @if($order->status === 'delivered')
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">{{ __('Delivered') }}</h4>
                        @if($order->status === 'delivered')
                            <p class="text-sm text-gray-600">{{ $order->delivered_at?->format('d/m/Y H:i') }}</p>
                        @else
                            <p class="text-sm text-gray-600">{{ __('Pending delivery') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tracking Link -->
        @if($order->tracking_url)
        <div class="mt-8 pt-8 border-t border-gray-200">
            <div class="bg-blue-50 rounded-lg p-6">
                <h4 class="font-bold text-gray-900 mb-2">{{ __('Tracking Number:') }}</h4>
                <p class="text-2xl font-mono font-bold text-blue-600 mb-4">{{ $order->tracking_number }}</p>
                <a href="{{ $order->tracking_url }}" 
                   target="_blank"
                   class="inline-block px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
                    {{ __('Track on Carrier Website') }} →
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Order Details -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Order Details') }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">{{ __('Customer Information') }}</h4>
                <p class="text-gray-900">{{ $order->customer_name }}</p>
                <p class="text-gray-600">{{ $order->customer_email }}</p>
                <p class="text-gray-600">{{ $order->customer_phone }}</p>
            </div>
            
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">{{ __('Shipping Address') }}</h4>
                <p class="text-gray-900">{{ $order->shipping_address }}</p>
                <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="font-semibold text-gray-700 mb-4">{{ __('Order Items') }}</h4>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                        <p class="text-sm text-gray-600">{{ __('Quantity:') }} {{ $item->quantity }}</p>
                    </div>
                    <p class="font-bold text-gray-900">{{ number_format($item->subtotal, 2) }} ₺</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="space-y-2">
                <div class="flex justify-between text-gray-700">
                    <span>{{ __('Subtotal') }}</span>
                    <span>{{ number_format($order->subtotal, 2) }} ₺</span>
                </div>
                @if($order->shipping_cost > 0)
                <div class="flex justify-between text-gray-700">
                    <span>{{ __('Shipping') }}</span>
                    <span>{{ number_format($order->shipping_cost, 2) }} ₺</span>
                </div>
                @endif
                @if($order->coupon_discount > 0)
                <div class="flex justify-between text-green-600">
                    <span>{{ __('Discount') }} ({{ $order->coupon_code }})</span>
                    <span>-{{ number_format($order->coupon_discount, 2) }} ₺</span>
                </div>
                @endif
                <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                    <span>{{ __('Total') }}</span>
                    <span>{{ number_format($order->total, 2) }} ₺</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 text-center">
        <a href="{{ route('order-tracking.index') }}" 
           class="inline-block px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors">
            ← {{ __('Track Another Order') }}
        </a>
    </div>
</div>
@endsection
