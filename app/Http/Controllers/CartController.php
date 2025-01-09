<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cartItems = $cart->items;

        return view('cart', compact('cartItems'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);

        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Product added to cart!');
    }

    public function remove(Request $request, $id)
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart')->with('success', 'Product removed from cart!');
    }
/*
    public function clear()
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cart->items()->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }*/

    public function update(Request $request, $id)
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }

        return response()->json(['success' => true]);
    }


/*
    public function checkout(Request $request) 
    { 
        $selectedItems = $request->input('selected_items', []); 
        $cartItems = CartItem::whereIn('id', $selectedItems)->get(); 
        return view('checkout.index', compact('cartItems')); 
    }*/

}
