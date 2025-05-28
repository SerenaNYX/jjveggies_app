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
                        <th>Order No.</th>                      
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
                        <td>{{ $order->order_number }}</td>
                        <td>
                            <div style="display: inline-flex;">
                                @foreach($order->items->take(2) as $item) 
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" style="max-height: 50px; width: auto; object-fit: scale-down;">
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
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        background-color: #d4edda;
    }
</style>

<style>
/* Mobile Orders Styles */
@media (max-width: 768px) {
    .clean-table {
        box-shadow: none;
        background: transparent;
    }

    .clean-table thead {
        display: none;
    }

    .clean-table tbody tr {
        display: flex;
        flex-wrap: wrap;
        background: #fff;
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
    }

    .clean-table td {
        padding: 5px;
        border: none;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* First column - order ID */
    .clean-table td:first-child {
        position: absolute;
        top: 15px;
        left: 15px;
        width: auto;
        font-weight: bold;
    }

    /* Second column - items */
    .clean-table td:nth-child(2) {
        width: 100%;
        padding-top: 35px;
        order: 1;
    }

    /* Third column - quantity */
    .clean-table td:nth-child(3) {
        width: 25%;
        order: 3;
        font-weight: bold;
        justify-content: flex-end;
    }
    .clean-table td:nth-child(3)::after {
        content: "\ item";
    }

    /* Fourth column - total */
    .clean-table td:nth-child(4) {
        width: 100%;
        order: 4;
        font-weight: bold;
        margin-top: 10px;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }

    /* Fifth column - date */
    .clean-table td:nth-child(5) {
        width: 60%;
        order: 5;
        font-size: 14px;
        color: #666;
        justify-content: flex-start;
    }

    /* Sixth column - status */
    .clean-table td:nth-child(6) {
        width: 40%;
        order: 6;
        justify-content: flex-end;
    }

    /* Items images */
    .clean-table td:nth-child(2) img {
        height: 40px;
        object-fit: scale-down;
    }

    /* Status badge */
    .badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 14px;
    }

    .bg-success {
        background-color: #d4edda !important;
        color: #155724 !important;
    }

    .bg-danger {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    .bg-primary {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
    }

    /* Empty orders */
    .text-center .fa-frown-o {
        font-size: 3em;
        color: #969696;
    }

    .text-center h3 {
        font-size: 20px;
    }

    .text-center p {
        font-size: 16px;
    }

    .text-center .btn {
        padding: 10px 20px;
        font-size: 16px;
    }

    /* Pagination */
    .pagination {
        margin-top: 20px;
        margin-bottom: 80px; /* Extra space for mobile */
    }

    .pagination ul {
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination li {
        margin: 3px;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    .clean-table td:nth-child(2) {
        width: 25%;
    }

    .clean-table td:nth-child(3) {
        width: 75%;
        font-size: 15px;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    .clean-table td:nth-child(3),
    .clean-table td:nth-child(4),
    .clean-table td:nth-child(5),
    .clean-table td:nth-child(6) {
        font-size: 14px;
    }

    .clean-table td:nth-child(2) img {
        height: 35px;
    }

    .badge {
        padding: 4px 8px;
        font-size: 13px;
    }
}

/* Hover effect for mobile */
@media (hover: hover) {
    .clean-table tr:hover {
        background-color: #f6fdf2;
    }
}
    </style>