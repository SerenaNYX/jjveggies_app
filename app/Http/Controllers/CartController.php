<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);

        $cartItems = $cart->items()
            ->with(['product', 'option'])
            ->orderBy('updated_at', 'DESC')  // Sort by last modification time
            ->get();
        
        return view('cart', compact('cartItems'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);

        if ($product->options->isNotEmpty()) {
            $request->validate([
                'option_id' => 'required|exists:product_options,id',
            ]);
        }

        if ($request->has('option_id')) {
            $option = ProductOption::findOrFail($request->option_id);

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->where('option_id', $option->id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity++;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'option_id' => $option->id,
                    'quantity' => 1,
                ]);
            }
        } else {
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->whereNull('option_id')
                ->first();

            if ($cartItem) {
                $cartItem->quantity++;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function remove(Request $request, $id)
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
        }

        return redirect()->route('cart.index')->with('error', 'Product not found in cart!');
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::id();
        $cart = Cart::firstOrCreate(['user_id' => $user_id]);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $id)
            ->with(['option'])
            ->first();

        if ($cartItem) {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $quantity = $request->input('quantity');
            $cartItem->quantity = $quantity;
            $cartItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'quantity' => $quantity,
                'subtotal' => number_format($cartItem->option->price * $quantity, 2)
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Cart item not found'
        ], 404);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:cart_items,id,user_id,'.Auth::id()
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())
            ->whereIn('id', $request->selected_items)
            ->with(['product', 'option'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items selected');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->option->price * $item->quantity;
        });

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'selected_items' => $request->selected_items
        ]);
    }
}
