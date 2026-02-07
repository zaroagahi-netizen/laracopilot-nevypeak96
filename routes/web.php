<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\OrderTrackingController;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
    
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    
    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');
    
    Route::get('/order-tracking', [OrderTrackingController::class, 'index'])->name('order-tracking.index');
    Route::post('/order-tracking', [OrderTrackingController::class, 'track'])->name('order-tracking.track');
    
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    
    Route::get('/about', function () {
        return view('about');
    })->name('about');
    
    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');
});

// Fallback route for root without locale
Route::get('/', function () {
    return redirect(LaravelLocalization::getLocalizedURL(config('app.locale')));
});