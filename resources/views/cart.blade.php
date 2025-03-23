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
                        <th class="column-option">Option</th> <!-- New column for product option -->
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
                                    data-price="{{ $item->option->price }}"
                                    data-quantity="{{ $item->quantity }}" 
                                    onclick="updateTotal()">
                            </td>
                            <td>
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="70" height="70">
                            </td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->option->option }}</td>
                            <td>
                                <button type="button" class="btn-quantity" onclick="updateQuantityInCart('{{ $item->id }}', 'decrease')">-</button>
                                <input type="number" class="cart-quantity" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" min="1" readonly>
                                <button type="button" class="btn-quantity" onclick="updateQuantityInCart('{{ $item->id }}', 'increase')">+</button>
                            </td>
                            <td>RM{{ number_format($item->option->price, 2) }}</td>
                            <td id="subtotal-{{ $item->id }}">RM{{ number_format($item->option->price * $item->quantity, 2) }}</td>
                            <td>                      
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" id="remove-form-{{ $item->id }}">
                                    <p>Item ID: {{ $item->id }}</p> <!--DELETE LATER-->
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
                <div class="select-all-container">
                    <input type="checkbox" id="select-all-toggle" onclick="toggleSelectAll()"></input>
                    <label class="all-label">All</label>
                    <span id="total-selected-items"> - (0 items selected)</span>
                </div>

                <h3>Total Price: RM <span id="total-price">0.00</span></h3>
                <input type="hidden" name="total_price" id="total_price" value="0">
                <button type="submit" class="btn-checkout" id="checkout-button" disabled>Proceed to Checkout</button>
            </div>
        </form>
    </div>
</div>

<script>

    // Function to toggle select all checkboxes
    function toggleSelectAll() {
        console.log('Available forms:', document.forms);

        const selectAllToggle = document.getElementById('select-all-toggle');
        const checkboxes = document.querySelectorAll('input[type="checkbox"].checkbox-quantity');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllToggle.checked;
        });

        updateTotal(); // Recalculate the total after the checkboxes are updated
    }


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
        //console.log('total is being updated');
        let total = 0;
        let itemCount = 0;
        
        document.querySelectorAll('input.checkbox-quantity:checked').forEach(function(checkbox) {
            const itemId = checkbox.value;
            const quantity = document.getElementById('quantity-' + itemId).value;
            const price = parseFloat(checkbox.dataset.price);
            total += price * quantity;
            itemCount++;
        });

        // Update total price on the page
        document.getElementById('total-price').innerText = total.toFixed(2);
        document.getElementById('total_price').value = total.toFixed(2);
        //console.log('Total is updated');
        document.getElementById('total-selected-items').innerText = ` - (${itemCount} items selected)`;
        
        // Enable or disable the checkout button based on item count
        const checkoutButton = document.getElementById('checkout-button');
        checkoutButton.disabled = itemCount === 0; // Disable if no items are selected
    }

    // Initial update of the total price when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });

    // Remove item from the cart
    function removeItem(id) {
        console.log('Attempting to remove form with ID:', 'remove-form-' + id);

        if (confirm('Are you sure?')) {
            const form = document.getElementById('remove-form-' + id);
            if (form) {
                form.submit();
            } else {
                console.error('Form not found for ID: ', id);
            }
        }
    }
</script>

@endsection