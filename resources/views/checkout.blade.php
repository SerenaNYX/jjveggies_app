@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Checkout</h1>
    <div class="cart-container">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Option</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td>
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="70" height="70">
                        </td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->option->option }}</td> <!-- Display selected option -->
                        <td>{{ $item->quantity }}</td>
                        <td>RM{{ number_format($item->option->price, 2) }}</td> <!-- Use option price -->
                        <td>RM{{ number_format($item->option->price * $item->quantity, 2) }}</td> <!-- Use option price for subtotal -->
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Payment Method Section -->
        <div>
            <h3>Payment method</h3>
            <button class="button">#</button>
            <button class="button">#</button>
            <button class="button">#</button>
        </div>

        <!-- Shipping Address Section -->
        <div>
            <h3>Shipping address</h3>
            <p># your shipping address</p>
        </div>

        <!-- Rewards Section -->
        <div>
            <h3>Apply reward</h3>
            <p>No rewards applied</p>
        </div>

        <!-- Total Price Section -->
        <div class="total-container2"> <!--###APPLY STYLING TO THIS-->
            <h3>Total Price: RM{{ number_format($totalPrice, 2) }}</h3>
        </div>

        <!-- Checkout Form (Commented Out for Now) -->
        <!--
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="items[]" value="{{ $cartItems->pluck('id')->join(',') }}">
            <input type="hidden" name="total_price" value="{{ $totalPrice }}">
            <div class="mt-4">
                <label for="address" class="block font-medium text-gray-700">Address</label>
                <input id="address" type="text" name="address" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">Proceed</button>
        </form>
        -->
    </div>
</div>
@endsection