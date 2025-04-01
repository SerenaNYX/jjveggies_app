<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{/*
    public function index(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);
        $totalPrice = $request->input('total_price', 0);

        if (count($selectedItems) > 0) {
            $cartItems = CartItem::whereIn('id', $selectedItems)
                ->with(['product', 'option'])
                ->get();
        } else {
            $cartItems = collect();
        }

        // Get user's addresses, ordered by most recent
        $addresses = Auth::user()->addresses()->latest()->get();
        $defaultAddress = $addresses->first();

        return view('checkout', compact('cartItems', 'totalPrice', 'addresses', 'defaultAddress'));
    }
*/
 /*   public function index(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);
        $totalPrice = $request->input('total_price', 0);

        if (count($selectedItems) > 0) {
            $cartItems = CartItem::whereIn('id', $selectedItems)
                ->with(['product', 'option'])
                ->get();
        } else {
            $cartItems = collect();
        }

        // Redirect to cart if no items are selected or cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty or no items selected.');
        }

        // Get user's addresses, ordered by most recent
        $addresses = Auth::user()->addresses()->latest()->get();
        $defaultAddress = $addresses->first();

        return view('checkout', compact('cartItems', 'totalPrice', 'addresses', 'defaultAddress'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,fpx,cod',
            'address_id' => 'required|exists:addresses,id,user_id,'.Auth::id(),
            'total_price' => 'required|numeric',
        ]);
        
        // Get the selected address
        $address = Address::find($request->address_id);
        
        // Handle different payment methods
        if (in_array($request->payment_method, ['stripe', 'fpx'])) {
            return redirect()->route('stripe.payment', [
                'price' => $request->total_price,
                'address_id' => $address->id,
                'payment_method' => $request->payment_method // Pass the payment method
            ]);
        }
        
        // Handle Cash on Delivery
        // Create order with the selected address
        // Clear cart, etc.
        
        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    }*/

    // In your CheckoutController.php
    public function index(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);
        $totalPrice = $request->input('total_price', 0);

        if (count($selectedItems) > 0) {
            $cartItems = CartItem::whereIn('id', $selectedItems)
                ->with(['product', 'option'])
                ->get();
        } else {
            $cartItems = collect();
        }

        // Redirect if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Calculate totals with delivery fee
        $deliveryFee = 6.00;
        $grandTotal = $totalPrice + $deliveryFee;

        // Get addresses
        $addresses = Auth::user()->addresses()->latest()->get();
        $defaultAddress = $addresses->first();

        return view('checkout', compact(
            'cartItems', 
            'totalPrice',
            'deliveryFee',
            'grandTotal',
            'addresses',
            'defaultAddress'
        ));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,fpx,cod',
            'address_id' => 'required|exists:addresses,id,user_id,'.Auth::id(),
            'grand_total' => 'required|numeric|min:0',
            'delivery_fee' => 'required|numeric',
        ]);
        
        // For Stripe/FPX payments
        if (in_array($request->payment_method, ['stripe', 'fpx'])) {
            // Use grand_total (subtotal + delivery fee)
            return redirect()->route('stripe.payment', [
                'price' => $request->grand_total,
                'payment_method' => $request->payment_method,
                'address_id' => $request->address_id
            ]);
        }
        
        // For COD - process normally
        // ... your COD logic ...
    }
}
