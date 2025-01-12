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

        if(count($selectedItems) > 0) {
            $cartItems = CartItem::whereIn('id', $selectedItems)->get();
        } else {
            $cartItems = collect();
        }

        return view('checkout', compact('cartItems', 'totalPrice'));
    }

}

