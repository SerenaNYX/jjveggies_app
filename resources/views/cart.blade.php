@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <h1 class="text-center">Shopping Cart</h1>
    <div class="cart-container">
        @if($cartItems->isEmpty())
            <div class="text-center">
                <div class="">
                    <i class="fas fa-shopping-cart fa-5x" style="color: #969696;"></i>
                </div>
                <h3 class="">Your cart is empty!</h3>
                <p class="">Looks like you haven't added any items yet</p>
                <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg" style="color:white; font-weight: bold;">
                    <i class="fas fa-arrow-left"></i>
                    <label style="cursor: pointer;">Continue Shopping</label>
                </a>
            </div>
        @else
            <form action="{{ route('checkout.index') }}" method="GET">
                <table class="clean-table"> <!-- or cart-table -->
                    <thead>
                        <tr>
                            <th><!--Select--></th>
                            <th class="column-image">Image</th>
                            <th class="column-product">Product</th>
                    <!--     <th class="column-option">Option</th>-->
                            <th>Price (per unit)</th>
                            <th class="column-quantity">Quantity</th>
                         
                    <!--       <th>Subtotal</th>-->
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
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="70" height="70" style="object-fit: scale-down;">
                                </td>
                                <td><strong>{{ $item->product->name }} </strong>({{ $item->option->option }})</td>
                                <td>RM{{ number_format($item->option->price, 2) }}</td>
                                <td>
                                    <div style="display: block;">
                                        <button type="button" class="btn-quantity" onclick="updateQuantityInCart('{{ $item->id }}', 'decrease')">-</button>
                                        <input type="number" class="cart-quantity" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" min="1" readonly>
                                        <button type="button" class="btn-quantity" onclick="updateQuantityInCart('{{ $item->id }}', 'increase')">+</button>
                                    </div>
                                </td>
                                
                    <!--           <td id="subtotal-{{ $item->id }}">RM{{ number_format($item->option->price * $item->quantity, 2) }}</td>-->
                                <td>                  
                                    <button type="button" class="btn-remove" onclick="removeItem({{ $item->id }})">Remove</button>
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
        @endif
    </div>
</div>

<script>
    function removeItem(id) {
        if (confirm('Are you sure?')) {
            fetch(`/cart/remove/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            }).then(() => window.location.reload()); // Refresh to update UI
        }
    }

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


    function updateQuantityInCart(id, action) {
        const quantityInput = document.getElementById('quantity-' + id);
        let quantity = parseInt(quantityInput.value);
        const checkbox = document.querySelector(`input[name="selected_items[]"][value="${id}"]`);
        const price = parseFloat(checkbox.dataset.price);

        // Adjust quantity based on action
        if (action === 'increase') {
            quantity++;
        } else if (action === 'decrease' && quantity > 1) {
            quantity--;
        } else {
            return; // No change needed
        }

        // Send update to server first
        fetch(`/cart/update/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Only update UI after successful server update
                quantityInput.value = data.quantity;
                if (document.getElementById('subtotal-' + id)) {
                    document.getElementById('subtotal-' + id).innerText = 
                        'RM' + data.subtotal;
                }
                updateTotal();
            } else {
                throw new Error(data.message || 'Update failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update quantity. Please try again.');
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

</script>

@endsection

<style>
@media (max-width: 768px) {
    /* First column - checkbox */
    .clean-table td:first-child {
        position: absolute;
        top: 15px;
        left: 15px;
        width: auto;
    }

    /* Second column - image */
    .clean-table td:nth-child(2) {
        width: 30%;
        padding-left: 35px;
    }

    /* Third column - product name */
    .clean-table td:nth-child(3) {
        width: 70%;
        font-size: 16px;
        align-items: center;
    }

    /* Fourth column - price */
    .clean-table td:nth-child(4) {
        width: 100%;
        order: 4;
        font-weight: bold;
        margin-top: 10px;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }

    /* Fifth column - quantity */
    .clean-table td:nth-child(5) {
        width: 50%;
        order: 5;
        justify-content: flex-start;
    }

    /* Sixth column - actions */
    .clean-table td:nth-child(6) {
        width: 50%;
        order: 6;
        justify-content: flex-end;
    }

    /* Quantity controls */
    .clean-table td:nth-child(4) .btn-quantity {
        width: 30px;
        height: 30px;
        font-size: 16px;
    }

    .clean-table td:nth-child(4) .cart-quantity {
        width: 40px;
        height: 30px;
        margin: 0 5px;
    }

    /* Remove button */
    .btn-remove {
        padding: 5px 15px;
        font-size: 14px;
    }

    /* Total container */
    .total-container {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 10px 15px;
        flex-direction: column;
        align-items: stretch;
    }

    .select-all-container {
        font-size: 14px !important;
        margin-bottom: -0.5rem !important;
    }

    .total-container h3 {
        font-size: 16px !important;
        text-align: center;
    }

    .btn-checkout {
        width: 100%;
        font-size: 16px !important;
    }

    /* Empty cart */
    .cart-container .text-center .fa-shopping-cart {
        font-size: 3em;
    }

    .cart-container .text-center h3 {
        font-size: 20px;
    }

    .cart-container .text-center p {
        font-size: 16px;
    }

    .cart-container .text-center .btn {
        padding: 10px 20px;
        font-size: 16px;
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

    .btn-remove {
        padding: 5px 10px;
        font-size: 13px;
    }
}
</style>
