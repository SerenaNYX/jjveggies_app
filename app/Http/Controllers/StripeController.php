<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function payment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Validate inputs
        $request->validate([
            'price' => 'required|numeric|min:1',
            'payment_method' => 'required|in:stripe,fpx'
        ]);

        $successURL = route('stripe.payment.success').'?session_id={CHECKOUT_SESSION_ID}';

        try {
            $paymentMethodTypes = ['card'];
            if ($request->payment_method === 'fpx') {
                $paymentMethodTypes = ['fpx'];
            }

            $session = Session::create([
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => ['name' => 'Order Total'],
                        'unit_amount' => $request->price * 100, // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $successURL,
                'cancel_url' => route('checkout.index'),
            ]);

            return redirect()->away($session->url);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: '.$e->getMessage());
        }
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}