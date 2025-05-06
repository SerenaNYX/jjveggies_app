<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\UserPointsHistory;
use App\Models\OrderStatusHistory;
use App\Http\Controllers\Controller;

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

   /* public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:order_placed,preparing,packed,delivering,delivered,completed,cancelled',
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
    }*/

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:order_placed,preparing,packed,delivering,delivered,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $user = auth('employee')->user();
        $currentStatus = $order->status;

        // Validate status transitions based on user role
        if ($user->role === 'staff') {
            if (!(
                ($currentStatus === 'order_placed' && $request->status === 'preparing') ||
                ($currentStatus === 'preparing' && $request->status === 'packed') ||
                ($currentStatus === 'delivered' && $request->status === 'completed') || // Add this line
                ($currentStatus === $request->status)
            )) {
                return back()->with('error', 'Invalid status transition');
            }
        }

        // Driver can only update from packed to delivering, or delivering to delivered
        if ($user->role === 'driver') {
            if (!(
                ($currentStatus === 'packed' && $request->status === 'delivering') ||
                ($currentStatus === 'delivering' && $request->status === 'delivered') ||
                ($currentStatus === $request->status)
            )) {
                return back()->with('error', 'Invalid status transition');
            }
        }

        // Award points when changing from delivered to completed
        if ($currentStatus === 'delivered' && $request->status === 'completed') {
            $points = floor($order->total); // 1 point per RM1 spent
            
            $order->user->points += $points;
            $order->user->save();
            
            // Record in points history
            UserPointsHistory::create([
                'user_id' => $order->user_id,
                'points' => $points,
                'source' => 'purchase',
                'reference_type' => Order::class,
                'reference_id' => $order->id
            ]);
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