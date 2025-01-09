<!-- resources/views/cart/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Shopping Cart</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="cart-container">
            <form action="{{ route('checkout.index') }}" method="GET"> <!-- TODO: should change to checkout later -->
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        <!--    <th>Subtotal</th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" data-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}" onclick="updateTotal()">
                                </td>
                                <td>
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="70" height="70"></a>
                                </td>
                                <td>{{ $item->product->name }}</td>
                                <td>
                                    <button type="button" onclick="updateQuantityInCart('{{ $item->id }}', 'decrease')">-</button>
                                    <input type="number" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" min="1" readonly>
                                    <button type="button" onclick="updateQuantityInCart('{{ $item->id }}', 'increase')">+</button>
                                </td>
                                <td>{{ number_format($item->product->price, 2) }}</td>
                            <!--    <td id="subtotal-{{ $item->id }}">{{ number_format($item->product->price * $item->quantity, 2) }}</td> -->
                                <td>
                                    <form action="{{ route('cart.remove', $item->product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-remove" onclick="return confirm('Are you sure?')">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-container">
                    <h3>Total Price: RM <span id="total-price">0.00</span></h3>
                    <input type="hidden" name="total_price" id="total_price" value="0">
                    <button type="submit" class="btn-checkout">Proceed to Checkout</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update total price based on selected items
        function updateTotal() {
            let total = 0;
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
                total += parseFloat(checkbox.dataset.price) * parseInt(checkbox.dataset.quantity);
            });
            document.getElementById('total-price').innerText = total.toFixed(2);
        }

        // Update the quantity and subtotal
        function updateQuantityInCart(id, action) {
            const quantityInput = document.getElementById('quantity-' + id);
            let quantity = parseInt(quantityInput.value);

            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            quantityInput.value = quantity;
            document.querySelector(`#checkbox-${id}`).dataset.quantity = quantity;

            const price = parseFloat(document.querySelector(`#checkbox-${id}`).dataset.price);
            const subtotal = (price * quantity).toFixed(2);
            document.getElementById('subtotal-' + id).innerText = subtotal;

            updateTotal();

            fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Cart item updated');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
@endsection
