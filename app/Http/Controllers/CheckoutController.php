<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);
        $totalPrice = $request->input('total_price', 0);

        if (count($selectedItems) > 0) {
            // Fetch cart items with their associated product and option
            $cartItems = CartItem::whereIn('id', $selectedItems)
                ->with(['product', 'option']) // Load the product and option relationships
                ->get();
        } else {
            $cartItems = collect();
        }

        return view('checkout', compact('cartItems', 'totalPrice'));
    }
}
