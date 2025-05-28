<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Voucher;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    public function payment(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $request->validate([
        'price' => 'required|numeric',
        'payment_method' => 'required|in:card,fpx',
        'address_id' => 'required|exists:addresses,id,user_id,'.Auth::id(),
        'selected_items' => 'required|array',
        'selected_items.*' => 'exists:cart_items,id',
        'voucher_code' => 'nullable|exists:vouchers,code'
    ]);

    // Store checkout data in session
    session([
        'checkout_data' => [
            'address_id' => $request->address_id,
            'payment_method' => $request->payment_method,
            'selected_items' => $request->selected_items,
            'delivery_fee' => 6.00,
            'grand_total' => $request->price,
            'voucher_code' => $request->voucher_code ?? null
        ]
    ]);

    try {
        $paymentMethodTypes = $request->payment_method === 'fpx' ? ['fpx'] : ['card'];

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
            'success_url' => route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.index'),
        ]);

        return redirect()->away($session->url);
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Payment failed: '.$e->getMessage());
    }
}

    public function success(Request $request)
    {
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('checkout.index')
                ->with('error', 'Checkout session expired');
        }

        $cartItems = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereIn('id', $checkoutData['selected_items'])
            ->with(['product', 'option'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items found');
        }

        $order = app(CheckoutController::class)->createOrder(
            $checkoutData['address_id'],
            $cartItems->sum(fn($item) => $item->option->price * $item->quantity),
            $checkoutData['delivery_fee'],
            $checkoutData['grand_total'],
            $checkoutData['payment_method'],
            'paid',
            $cartItems,
            $checkoutData['voucher_code'] ?? null
        );

        // Remove only selected items
        CartItem::whereIn('id', $checkoutData['selected_items'])
            ->whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->delete();

        session()->forget('checkout_data');

        return view('checkout.success', compact('order'));
    }

    public function cancel()
    {
        session()->forget('checkout_data');
        return view('checkout.cancel');
    }
}