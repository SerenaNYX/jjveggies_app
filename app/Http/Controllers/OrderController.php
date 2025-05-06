<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'statusHistory'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'items.option', 'address', 'statusHistory']);

        return view('orders.show', compact('order'));
    }

    public function completeOrder(Order $order)
    {
        // Only admin can complete orders
        if (!Auth::user()->is_admin) {
            abort(403);
        }
        
        if ($order->status !== 'completed') {
            $order->update(['status' => 'completed']);
            
            // Award points based on purchase amount (1 point per RM1 spent)
            $points = floor($order->total);
            $order->user->addPoints($points, 'purchase', $order);
            
            $order->statusHistory()->create([
                'status' => 'completed',
                'notes' => 'Order completed and points awarded'
            ]);
            
            return back()->with('success', 'Order marked as completed and points awarded.');
        }
        
        return back()->with('error', 'Order is already completed.');
    }
}