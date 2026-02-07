<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\LocalizedMail;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = $request->email;
        $locale = app()->getLocale();

        // Store subscription in database (create Subscription model if needed)
        // Subscription::create(['email' => $email, 'locale' => $locale]);

        // Send welcome email with free PDF
        Mail::to($email)->queue(
            new LocalizedMail(
                locale: $locale,
                viewName: 'emails.subscription-welcome',
                data: [
                    'subject' => __('Welcome to ZARO! Your Free PDF Inside'),
                    'email' => $email,
                ]
            )
        );

        return response()->json([
            'success' => true,
            'message' => __('Subscription successful! Check your email.'),
        ]);
    }
}