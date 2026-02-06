<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
    
    Route::get('/products', function () {
        return view('products');
    })->name('products');
    
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