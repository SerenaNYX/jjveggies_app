@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Orders</h1>
    
    @if($orders->isEmpty())
        <div class="alert alert-info">
            You haven't placed any orders yet.
        </div>
    @else
        <div>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at->format("d/m/Y H:i:s") }}</td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td>RM{{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($order->status === 'completed') bg-success
                                @elseif($order->status === 'cancelled') bg-danger
                                @else bg-primary
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($order->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn" style="color:white;">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>           

        
        <div class="pagination">
            {{ $orders->links('vendor.pagination.default') }}
        </div>
    @endif
</div>
@endsection