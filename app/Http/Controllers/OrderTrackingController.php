<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('order-tracking.index');
    }

    public function track(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|string',
            'verification' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $orderNumber = strtoupper(trim($request->order_number));
        $verification = trim($request->verification);

        // Try to find order by order number
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->back()
                ->with('error', __('Order not found'))
                ->withInput();
        }

        // Verify with email or phone
        $isVerified = false;
        
        if (strtolower($order->customer_email) === strtolower($verification)) {
            $isVerified = true;
        } elseif (str_replace([' ', '-', '(', ')'], '', $order->customer_phone) === str_replace([' ', '-', '(', ')'], '', $verification)) {
            $isVerified = true;
        }

        if (!$isVerified) {
            return redirect()->back()
                ->with('error', __('Verification failed. Please check your email or phone number.'))
                ->withInput();
        }

        return view('order-tracking.show', compact('order'));
    }
}