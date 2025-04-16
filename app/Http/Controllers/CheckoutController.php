<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);
    
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'No items selected');
        }

        $cartItems = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereIn('id', $selectedItems)
            ->with(['product', 'option'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Selected items not found');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->option->price * $item->quantity;
        });

        $deliveryFee = 6.00;
        $grandTotal = $totalPrice + $deliveryFee;
        $addresses = Auth::user()->addresses()->latest()->get();
        $defaultAddress = $addresses->first();

        return view('checkout', compact(
            'cartItems', 
            'totalPrice',
            'deliveryFee',
            'grandTotal',
            'addresses',
            'defaultAddress',
            'selectedItems'
        ));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,fpx',
            'address_id' => 'required|exists:addresses,id,user_id,'.Auth::id(),
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:cart_items,id'
        ]);

        $cartItems = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereIn('id', $request->selected_items)
            ->with(['product', 'option'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items selected');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->option->price * $item->quantity;
        });

        $deliveryFee = 6.00;
        $grandTotal = $subtotal + $deliveryFee;

        // For Stripe/FPX payments
        if (in_array($request->payment_method, ['stripe', 'fpx'])) {
            return redirect()->route('stripe.payment', [
                'price' => $grandTotal,
                'payment_method' => $request->payment_method,
                'address_id' => $request->address_id,
                'selected_items' => $request->selected_items
            ]);
        }
    }

    public function createOrder($addressId, $subtotal, $deliveryFee, $total, $paymentMethod, $paymentStatus, $cartItems)
    {
        $address = Address::where('id', $addressId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $address->id,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'status' => 'order_placed'
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'option_id' => $item->option_id,
                'quantity' => $item->quantity,
                'price' => $item->option->price
            ]);
        }

        $order->statusHistory()->create([
            'status' => 'order_placed',
            'notes' => 'Order was placed by customer'
        ]);

        return $order;
    }
}