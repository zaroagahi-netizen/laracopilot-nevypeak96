<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['terms.taxonomy']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by age
        if ($request->filled('age')) {
            $query->forAge($request->age);
        }

        // Filter by type
        if ($request->filled('type')) {
            if ($request->type === 'physical') {
                $query->physical();
            } elseif ($request->type === 'digital') {
                $query->digital();
            }
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show($locale, $id)
    {
        $product = Product::with(['terms.taxonomy'])->findOrFail($id);

        // Generate WhatsApp message
        $message = __('whatsapp.order_message', [
            'product' => $product->name,
            'price' => number_format($product->price, 2),
            'url' => url(app()->getLocale() . '/products/' . $product->id)
        ]);

        $whatsappNumber = config('app.whatsapp_number');
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return view('products.show', compact('product', 'whatsappUrl'));
    }
}