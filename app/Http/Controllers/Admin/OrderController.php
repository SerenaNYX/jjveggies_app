<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\UserPointsHistory;
use App\Models\OrderStatusHistory;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth('employee')->user();
        
        $orders = Order::with(['user', 'items.product', 'driver'])
                    ->when(request('status'), function($query) {
                        $query->where('status', request('status'));
                    })
                    ->when($user->role === 'driver', function($query) use ($user) {
                        $query->where('driver_id', $user->id)
                            ->whereIn('status', ['packed', 'delivering', 'delivered']);
                    })
                    ->latest()
                    ->paginate(15);

        $drivers = $user->role === 'staff' ? Employee::where('role', 'driver')->get() : collect();

        return view('admin.orders.index', compact('orders', 'drivers'));
    }

    public function assignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => 'required|exists:employees,id,role,driver'
        ]);

        // Check if the order is in packed status
        if ($order->status !== 'packed') {
            return back()->with('error', 'Drivers can only be assigned to packed orders');
        }

        // Check if the user is authorized (staff only)
        if (auth('employee')->user()->role !== 'staff') {
            return back()->with('error', 'Only staff can assign drivers');
        }

        // Update the order with the assigned driver
        $order->update([
            'driver_id' => $request->driver_id
        ]);

        // Record in status history
        $order->statusHistory()->create([
            'status' => $order->status,
            'notes' => 'Driver assigned: ' . Employee::find($request->driver_id)->name,
            'changed_by' => auth('employee')->user()->name
        ]);

        return back()->with('success', 'Driver assigned successfully');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.option', 'address', 'statusHistory']);
        $drivers = Employee::where('role', 'driver')->get();
        
        return view('admin.orders.show', compact('order', 'drivers'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:order_placed,preparing,packed,delivering,delivered,completed,cancelled,refunded',
            'notes' => 'nullable|string',
            'driver_id' => 'nullable|exists:employees,id,role,driver'
        ]);

        $user = auth('employee')->user();
        $currentStatus = $order->status;

        // Validate status transitions based on user role
        if ($user->role === 'staff') {
            if (!(
                ($currentStatus === 'order_placed' && in_array($request->status, ['preparing', 'cancelled'])) ||
                ($currentStatus === 'preparing' && in_array($request->status, ['packed', 'cancelled'])) ||
                ($currentStatus === 'cancelled' && $request->status === 'refunded') ||
                ($currentStatus === 'delivered' && $request->status === 'completed') ||
                ($currentStatus === $request->status)
            )) {
                return back()->with('error', 'Invalid status transition');
            }

            // Handle cancellation
            if ($request->status === 'cancelled' && empty($request->notes)) {
                return back()->with('error', 'Cancellation reason is required');
            }
        }

        // Driver can only update from packed to delivering, or delivering to delivered
        if ($user->role === 'driver') {
            if (!(
                ($currentStatus === 'packed' && $request->status === 'delivering' && $order->driver_id === $user->id) ||
                ($currentStatus === 'delivering' && $request->status === 'delivered' && $order->driver_id === $user->id) ||
                ($currentStatus === $request->status)
            )) {
                return back()->with('error', 'Invalid status transition');
            }
        }

        // Update order
        if ($request->status === 'cancelled') {
            $order->cancel($request->notes, $user->name);
        } elseif ($request->status === 'refunded') {
            $order->refund($request->notes, $user->name);
        } else {
            $order->status = $request->status;
            
            // When changing to packed, assign the driver
            if ($request->status === 'packed') {
                $order->driver_id = $request->driver_id;
            }
            
            $order->save();

            $order->statusHistory()->create([
                'status' => $request->status,
                'notes' => $request->notes,
                'changed_by' => $user->name
            ]);
        }

        // Award points when changing from delivered to completed
        if ($currentStatus === 'delivered' && $request->status === 'completed') {
            $points = floor($order->total); // 1 point per RM1 spent
            
            $order->user->points += $points;
            $order->user->save();
            
            UserPointsHistory::create([
                'user_id' => $order->user_id,
                'points' => $points,
                'source' => 'purchase',
                'reference_type' => Order::class,
                'reference_id' => $order->id
            ]);
        }

        return back()->with('success', 'Order status updated successfully');
    }
}