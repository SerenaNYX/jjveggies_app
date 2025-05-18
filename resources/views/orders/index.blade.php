@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">My Orders</h1>
    
    @if($orders->isEmpty())
        <div class="text-center">
            <div class="">
                <i class="fas fa-frown-o fa-5x" style="color: #969696;"></i>
            </div>
            <h3 class="">You order is empty!</h3>
            <p class="">Seems like you haven't placed any orders yet</p>
            <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg" style="color:white; font-weight: bold;">
                <i class="fas fa-arrow-left"></i>
                <label style="cursor: pointer;">Continue Shopping</label>
            </a>
        </div>
    @else
        <div>
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>#</th>                      
                        <th>Items</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr onclick="window.location='{{ route('orders.show', $order->id) }}'" style="cursor: pointer;">
                        <td>{{ $order->id }}</td>
                        <td> 
                            <div>
                                @foreach($order->items->take(2) as $item) 
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" style="height: 50px; object-fit: cover;">
                                @endforeach
                                @if($order->items->count() > 2)
                                <small class="text-muted">+{{ $order->items->count() - 2 }} more</small>
                                @endif
                            </div>
                        </td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td>RM{{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->created_at->format("d/m/Y H:i:s") }}</td>
                        <td>
                            <span class="badge 
                                @if($order->status === 'completed') bg-success
                                @elseif($order->status === 'cancelled') bg-danger
                                @else bg-primary
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($order->status)) }}
                            </span>
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

<style>
    .clean-table tr:hover {
        transition: background-color 0.3s, color 0.3s;
        background-color: #f6fdf2;
    }    
</style>