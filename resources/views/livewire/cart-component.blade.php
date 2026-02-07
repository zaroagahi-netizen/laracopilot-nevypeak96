<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('Shopping Cart') }}</h1>

    @if($cart && $cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart->items as $item)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex gap-4">
                        <img src="{{ $item->product->primary_image ?? 'https://via.placeholder.com/100' }}" 
                             alt="{{ $item->product->name }}"
                             class="w-24 h-24 object-cover rounded">
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $item->product->name }}</h3>
                            <p class="text-gray-600 mb-2">{{ number_format($item->price, 2) }} ₺</p>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex items-center border rounded">
                                    <button 
                                        wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                        class="px-3 py-1 hover:bg-gray-100">
                                        -
                                    </button>
                                    <span class="px-4 py-1 border-x">{{ $item->quantity }}</span>
                                    <button 
                                        wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                        class="px-3 py-1 hover:bg-gray-100">
                                        +
                                    </button>
                                </div>
                                
                                <button 
                                    wire:click="removeItem({{ $item->id }})"
                                    class="text-red-600 hover:text-red-700 text-sm">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">{{ number_format($item->subtotal, 2) }} ₺</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Order Summary') }}</h2>
                    
                    <!-- Coupon Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Discount Code') }}</label>
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                wire:model="couponCode"
                                placeholder="{{ __('Enter code') }}"
                                class="flex-1 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ $appliedCoupon ? 'disabled' : '' }}>
                            
                            @if($appliedCoupon)
                                <button 
                                    wire:click="removeCoupon"
                                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                    {{ __('Remove') }}
                                </button>
                            @else
                                <button 
                                    wire:click="applyCoupon"
                                    class="px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600">
                                    {{ __('Apply') }}
                                </button>
                            @endif
                        </div>
                        
                        @if($couponMessage)
                            <p class="mt-2 text-sm {{ $couponError ? 'text-red-600' : 'text-green-600' }}">
                                {{ $couponMessage }}
                            </p>
                        @endif
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>{{ __('Subtotal') }}</span>
                            <span>{{ number_format($this->subtotal, 2) }} ₺</span>
                        </div>
                        
                        @if($appliedCoupon && $couponDiscount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>{{ __('Discount') }} ({{ $appliedCoupon->code }})</span>
                            <span>-{{ number_format($couponDiscount, 2) }} ₺</span>
                        </div>
                        @endif
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-xl font-bold text-gray-900">
                                <span>{{ __('Total') }}</span>
                                <span>{{ number_format($this->total, 2) }} ₺</span>
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ url(app()->getLocale() . '/checkout') }}" 
                       class="block w-full py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg text-center hover:from-primary-600 hover:to-primary-700 transition-all">
                        {{ __('Proceed to Checkout') }}
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Your cart is empty') }}</h2>
            <p class="text-gray-600 mb-6">{{ __('Add some products to get started!') }}</p>
            <a href="{{ url(app()->getLocale() . '/products') }}" 
               class="inline-block px-8 py-3 bg-primary-500 text-white font-bold rounded-lg hover:bg-primary-600 transition-colors">
                {{ __('Browse Products') }}
            </a>
        </div>
    @endif
</div>
