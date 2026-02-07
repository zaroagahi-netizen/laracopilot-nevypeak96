@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .product-image-main {
        aspect-ratio: 1;
        object-fit: cover;
    }
    .product-thumbnail {
        aspect-ratio: 1;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.3s;
    }
    .product-thumbnail:hover {
        transform: scale(1.05);
    }
    .product-thumbnail.active {
        border: 3px solid #f18f1e;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-8">
        <ol class="flex items-center gap-2">
            <li><a href="{{ url(app()->getLocale()) }}" class="text-gray-600 hover:text-primary-500">{{ __('Home') }}</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ url(app()->getLocale() . '/products') }}" class="text-gray-600 hover:text-primary-500">{{ __('Products') }}</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-medium">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-4">
                <img id="mainImage" 
                     src="{{ $product->primary_image ?? 'https://via.placeholder.com/600' }}" 
                     alt="{{ $product->name }}"
                     class="w-full product-image-main">
            </div>
            
            @if($product->images && count($product->images) > 1)
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images as $index => $image)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <img src="{{ $image }}" 
                         alt="{{ $product->name }} - {{ $index + 1 }}"
                         class="product-thumbnail {{ $index === 0 ? 'active' : '' }}"
                         onclick="changeMainImage('{{ $image }}', this)">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
            
            <!-- Badges -->
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($product->badges as $badge)
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($badge['type'] === 'new') bg-blue-100 text-blue-800
                    @elseif($badge['type'] === 'digital') bg-purple-100 text-purple-800
                    @elseif($badge['type'] === 'customizable') bg-pink-100 text-pink-800
                    @elseif($badge['type'] === 'discount') bg-red-100 text-red-800
                    @endif">
                    {{ $badge['label'] }}
                </span>
                @endforeach
            </div>

            <!-- Price -->
            <div class="mb-6">
                @if($product->compare_at_price && $product->compare_at_price > $product->price)
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-bold text-primary-600">{{ number_format($product->price, 2) }} ₺</span>
                    <span class="text-2xl text-gray-400 line-through">{{ number_format($product->compare_at_price, 2) }} ₺</span>
                </div>
                @else
                <span class="text-4xl font-bold text-primary-600">{{ number_format($product->price, 2) }} ₺</span>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="mb-6">
                <div class="flex items-center gap-2">
                    @if($product->stock_status === 'in_stock')
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-green-700 font-medium">{{ $product->stock_message }}</span>
                    @elseif($product->stock_status === 'low_stock')
                    <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                    <span class="text-orange-700 font-medium">{{ $product->stock_message }}</span>
                    @elseif($product->stock_status === 'out_of_stock')
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-red-700 font-medium">{{ $product->stock_message }}</span>
                    @else
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-blue-700 font-medium">{{ $product->stock_message }}</span>
                    @endif
                </div>
            </div>

            <!-- Age Range -->
            @if($product->min_age || $product->max_age)
            <div class="mb-6">
                <div class="flex items-center gap-2 text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>{{ __('Age Range:') }} 
                        @if($product->min_age && $product->max_age)
                            {{ $product->min_age }}-{{ $product->max_age }} {{ __('years') }}
                        @elseif($product->min_age)
                            {{ $product->min_age }}+ {{ __('years') }}
                        @elseif($product->max_age)
                            {{ __('Up to') }} {{ $product->max_age }} {{ __('years') }}
                        @endif
                    </span>
                </div>
            </div>
            @endif

            <!-- Taxonomies -->
            @if($product->developmentAreas->count() > 0)
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">{{ __('Development Areas:') }}</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->developmentAreas as $area)
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $area->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Description') }}</h3>
                <div class="text-gray-700 prose max-w-none">
                    {!! $product->description !!}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <!-- Quick Order Button -->
                <button 
                    onclick="quickOrder()"
                    class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105
                    {{ $product->stock_status === 'out_of_stock' ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{ __('Quick Order') }}
                    </div>
                </button>

                <!-- Add to Cart Button -->
                <button 
                    onclick="addToCart()"
                    class="w-full py-4 bg-white border-2 border-primary-500 text-primary-600 font-bold rounded-xl hover:bg-primary-50 transition-all duration-300
                    {{ $product->stock_status === 'out_of_stock' ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ __('Add to Cart') }}
                    </div>
                </button>

                <!-- WhatsApp Order Button -->
                <a 
                    href="{{ $whatsappUrl }}"
                    target="_blank"
                    class="w-full py-4 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    {{ __('Order via WhatsApp') }}
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeMainImage(src, element) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.product-thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

function quickOrder() {
    // Redirect to checkout with this product
    window.location.href = '{{ url(app()->getLocale() . "/checkout?product=" . $product->id) }}';
}

function addToCart() {
    // AJAX add to cart
    fetch('{{ url(app()->getLocale() . "/cart/add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('{{ __('Product added to cart!') }}');
            // Update cart count if you have one
        } else {
            alert(data.message || '{{ __('Failed to add product') }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __('An error occurred') }}');
    });
}
</script>
@endpush
@endsection
