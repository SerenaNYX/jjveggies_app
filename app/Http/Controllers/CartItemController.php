<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
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

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function remove(Request $request, $id)
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    /*public function update(Request $request, $id)
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cartItem = CartItems::where('cart_id', $cart->id)->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->subtotal_price = $cartItem->quantity * $cartItem->product->price;
            $cartItem->save();
        }

        return response()->json(['success' => true]);
    }*/

    public function update(Request $request, $id)
{
    $user_id = Auth::id();
    $cart = Cart::firstOrCreate(['user_id' => $user_id]);
    $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();

    if ($cartItem) {
        // Validate the quantity input
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the updated quantity
        $quantity = $request->input('quantity');
        
        // Update the cart item in the database
        $cartItem->quantity = $quantity;
        $cartItem->save(); // Save changes to the database

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated successfully',
            'quantity' => $quantity,
            'subtotal' => number_format($cartItem->product->price * $quantity, 2)
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Cart item not found']);
}


    public function destroy($id)
    {
        $product = CartItem::findOrFail($id);
        $product->delete();

        return redirect()->route('cart.index')->with('success', 'Cart item deleted successfully.');
    }

}
