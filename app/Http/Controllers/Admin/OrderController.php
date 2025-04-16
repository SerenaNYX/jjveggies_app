<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth('employee')->user();
        
        $orders = Order::with(['user', 'items.product'])
                    ->when(request('status'), function($query) {
                        $query->where('status', request('status'));
                    })
                    ->when($user->role === 'driver', function($query) {
                        // Drivers only see packed, delivering, or delivered orders
                        $query->whereIn('status', ['packed', 'delivering', 'delivered']);
                    })
                    ->latest()
                    ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.option', 'address', 'statusHistory']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:order_placed,preparing,packed,delivering,delivered,cancelled',
            'notes' => 'nullable|string'
        ]);

        $user = auth('employee')->user();
        $currentStatus = $order->status;

        // Staff can only update from order_placed to preparing, or preparing to packed
        if ($user->role === 'staff') {
            if (!(
                ($currentStatus === 'order_placed' && $request->status === 'preparing') ||
                ($currentStatus === 'preparing' && $request->status === 'packed') ||
                // Allow keeping same status (for notes updates)
                $currentStatus === $request->status
            )) {
                return back()->with('error', 'Invalid status transition');
            }
        }

        // Driver can only update from packed to delivering, or delivering to delivered
        if ($user->role === 'driver') {
            if (!(
                ($currentStatus === 'packed' && $request->status === 'delivering') ||
                ($currentStatus === 'delivering' && $request->status === 'delivered') ||
                // Allow keeping same status (for notes updates)
                $currentStatus === $request->status
            )) {
                return back()->with('error', 'Invalid status transition');
            }
        }

        $order->update(['status' => $request->status]);

        $order->statusHistory()->create([
            'status' => $request->status,
            'notes' => $request->notes,
            'changed_by' => $user->name
        ]);

        return back()->with('success', 'Order status updated successfully');
    }
}