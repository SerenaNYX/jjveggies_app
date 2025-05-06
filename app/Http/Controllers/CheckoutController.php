<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\Voucher;
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
        'selected_items.*' => 'exists:cart_items,id',
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

    // Apply voucher discount if provided
    if ($request->has('voucher_code')) {
        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('user_id', Auth::id())
            ->where('is_used', false)
            ->first();

        if ($voucher && $grandTotal >= $voucher->minimum_spend) {
            $grandTotal -= $voucher->discount_amount;
        }
    }

    // For Stripe/FPX payments
    if (in_array($request->payment_method, ['stripe', 'fpx'])) {
        return redirect()->route('stripe.payment', [
            'price' => $grandTotal,
            'payment_method' => $request->payment_method,
            'address_id' => $request->address_id,
            'selected_items' => $request->selected_items,
            'voucher_code' => $request->voucher_code ?? null
        ]);
    }
}
/*
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
    }*/

    public function createOrder($addressId, $subtotal, $deliveryFee, $total, $paymentMethod, $paymentStatus, $cartItems, $voucherCode = null)
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
            'status' => 'order_placed',
            'voucher_code' => $voucherCode,
            'discount_amount' => $voucherCode ? Voucher::where('code', $voucherCode)->value('discount_amount') : 0
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

        // Mark voucher as used if applicable
        if ($voucherCode) {
            Voucher::where('code', $voucherCode)
                ->where('user_id', Auth::id())
                ->update([
                    'is_used' => true,
                    'used_at' => now()
                ]);
        }

        $order->statusHistory()->create([
            'status' => 'order_placed',
            'notes' => 'Order was placed by customer'
        ]);

        return $order;
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|exists:vouchers,code',
            'grand_total' => 'required|numeric'
        ]);
        
        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('user_id', Auth::id())
            ->where('is_used', false)
            ->first();
        
        if (!$voucher) {
            return response()->json(['error' => 'Invalid or already used voucher code.'], 400);
        }
        
        if ($request->grand_total < $voucher->minimum_spend) {
            return response()->json([
                'error' => 'Minimum spend requirement not met for this voucher.'
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'discount_amount' => $voucher->discount_amount,
            'new_total' => max(0, $request->grand_total - $voucher->discount_amount),
            'voucher_code' => $voucher->code
        ]);
    }
}