@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <h1 class="text-center">Shopping Cart</h1>
    <div class="cart-container">
        <form action="{{ route('checkout.index') }}" method="GET">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th class="column-image">Image</th>
                        <th class="column-product">Product</th>
                        <th class="column-quantity">Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>
                                <input type="checkbox" 
                                class="checkbox-quantity" 
                                name="selected_items[]" 
                                value="{{ $item->id }}" 
                                data-price="{{ $item->product->price }}" 
                                data-quantity="{{ $item->quantity }}" 
                                onclick="updateTotal()">
                            </td>
                            <td>
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="70" height="70">
                            </td>
                            <td>{{ $item->product->name }}</td>
                            <td>
                                <button type="button" class="btn-quantity" onclick="updateQuantityInCart('{{ $item->id }}', 'decrease')">-</button>
                                <input type="number" class="cart-quantity" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" min="1" readonly>
                                <button type="button" class="btn-quantity" onclick="updateQuantityInCart('{{ $item->id }}', 'increase')">+</button>
                            </td>
                            <td>RM{{ number_format($item->product->price, 2) }}</td>
                            <td id="subtotal-{{ $item->id }}">RM{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item->product->id) }}" method="POST" id="remove-form-{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-remove" onclick="removeItem({{ $item->id }})">Remove</button>
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

    // Update quantity and subtotal for all items
    function updateQuantityInCart(id, action) {
        const quantityInput = document.getElementById('quantity-' + id);
        let quantity = parseInt(quantityInput.value);

        // Adjust quantity based on the action
        if (action === 'increase') {
            quantity++;
        } else if (action === 'decrease' && quantity > 1) {
            quantity--;
        }

        // Update the quantity field on the frontend
        quantityInput.value = quantity;

        const price = parseFloat(document.querySelector(`input[name="selected_items[]"][value="${id}"]`).dataset.price); 
        document.getElementById('subtotal-' + id).innerText = 'RM ' + (price * quantity).toFixed(2);
        updateTotal();

        // Send the updated quantity to the server
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
                console.log('Cart item updated successfully');
            } else {
                console.error('Failed to update cart item:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Update the total price for all selected items
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
            const itemId = checkbox.value;
            const quantity = document.getElementById('quantity-' + itemId).value;
            const price = parseFloat(checkbox.dataset.price);
            total += price * quantity;
        });

        // Update total price on the page
        document.getElementById('total-price').innerText = total.toFixed(2);
        document.getElementById('total_price').value = total.toFixed(2);
    }

    // Initial update of the total price when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });

    function removeItem(id) {
        if (confirm('Are you sure?')) {
            const form = document.getElementById('remove-form-' + id);
            console.log(form);
            if (form) {
                form.submit();
            } else {
                console.erorr('Form not founr for ID: ', id);
            }
            
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });
</script>
@endsection
