@extends('layouts.app')

@section('content')
<div class="container">

    @if($cartItems->isEmpty())
        <script>
            window.location.href = "{{ route('cart.index') }}";
        </script>
    @endif

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

        <div class="payment-methods">
            <h3>Payment method</h3>
            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method" id="payment-method" value="">
                <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" id="stripe-payment" name="payment_selection" value="stripe" class="hidden">
                        <label for="stripe-payment" class="payment-label">
                            <div class="payment-content">
                              <!--  <span>Credit/Debit Card</span>-->
                                <img src="{{ asset('img/stripe.png') }}" alt="Stripe" height="30">
                            </div>
                        </label>
                    </div>
                    
                    <div class="payment-option">
                        <input type="radio" id="fpx-payment" name="payment_selection" value="fpx" class="hidden">
                        <label for="fpx-payment" class="payment-label">
                            <div class="payment-content">
                              <!--  <span>FPX Online Banking</span>-->
                                <img src="{{ asset('img/fpx.png') }}" alt="FPX" height="30">
                            </div>
                        </label>
                    </div>
                </div>
                

        <!-- Address Section -->
        <div class="shipping-address-section">
            <h3>Shipping address</h3>
            <div class="selected-address" id="address-selection-trigger">
                @if($defaultAddress)
                    <p class="address-line">{{ $defaultAddress->address }}</p>
                    <p class="address-line">{{ $defaultAddress->postal_code }}</p>
                    <p class="address-line">{{ $defaultAddress->phone }}</p>
                @else
                    <p>No address saved. Please add one.</p>
                @endif
                <input type="hidden" name="address_id" id="selected-address-id" value="{{ $defaultAddress ? $defaultAddress->id : '' }}">
            </div>
        </div>

        <!-- Rewards Section -->
        <div>
            <h3>Apply reward</h3>
            <p>No rewards applied</p>
        </div>

        <!-- Total Price Section -->
        <h3>Payment Details</h3>
        <div class="total-container2">
            <span>Subtotal:</span>
            <span class="text-right">RM{{ number_format($totalPrice, 2) }}</span>
            
            <span>Delivery Fee:</span>
            <span class="text-right">RM6.00</span>
            
            <span class="grand-total-label">Total Payment:</span>
            <span class="grand-total text-right">RM{{ number_format($grandTotal, 2) }}</span>
        </div>


        <input type="hidden" name="delivery_fee" value="{{ $deliveryFee }}">
        <input type="hidden" name="grand_total" value="{{ $grandTotal }}">


        <div class="total-container">
            <h3>Total Payment: RM {{ number_format($grandTotal, 2) }}</h3>
            <button type="submit" class="btn-checkout">Complete Checkout</button>
        </div>

    </form>
    <!-- Address Selection Modal -->
    <div class="modal" id="address-modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Select Delivery Address</h3>
                            
            <button id="add-new-address-btn" class="button" type="button">Add New Address</button>
            
            <!-- New Address Form (initially hidden) -->
            <div id="new-address-form" style="display:none;">
                <form id="save-address-form">
                    @csrf
                    <div class="form-group">
                        <label for="new-address">Address</label>
                        <input type="text" id="new-address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="new-postal-code">Postal Code</label>
                        <input type="text" id="new-postal-code" name="postal_code" required>
                    </div>
                    <div class="form-group">
                        <label for="new-phone">Phone</label>
                        <input type="text" id="new-phone" name="phone" required>
                    </div>
                    <button type="submit" class="btn">Save Address</button>
                </form>
            </div>

            <div class="address-list">
                @foreach($addresses as $address)
                    <div class="address-option" data-address-id="{{ $address->id }}">
                        <p class="address-line">{{ $address->address }}</p>
                        <p class="address-line">{{ $address->postal_code }}</p>
                        <p class="address-line">{{ $address->phone }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
</div>



<script>

document.addEventListener('DOMContentLoaded', function() {
    // Address modal handling
    const modal = document.getElementById('address-modal');
    const trigger = document.getElementById('address-selection-trigger');
    const closeModal = document.querySelector('.close-modal');
    const addNewAddressBtn = document.getElementById('add-new-address-btn');
    const newAddressForm = document.getElementById('new-address-form');
    const addressList = document.querySelector('.address-list');
    
    // Show modal when address is clicked
    trigger.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'block';
        
        // Highlight currently selected address
        const selectedAddressId = document.getElementById('selected-address-id').value;
        if (selectedAddressId) {
            document.querySelectorAll('.address-option').forEach(option => {
                option.classList.remove('active');
                if (option.getAttribute('data-address-id') === selectedAddressId) {
                    option.classList.add('active');
                }
            });
        }
    });
    
    // Close modal
    closeModal.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'none';
        newAddressForm.style.display = 'none';
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            newAddressForm.style.display = 'none';
        }
    });
    
    // Function to handle address selection
    function setupAddressSelection(addressOption) {
        addressOption.addEventListener('click', function(e) {
            e.preventDefault();
            const addressId = this.getAttribute('data-address-id');
            
            // Remove active class from all address options
            document.querySelectorAll('.address-option').forEach(option => {
                option.classList.remove('active');
            });
            
            // Add active class to selected address
            this.classList.add('active');
            
            document.getElementById('selected-address-id').value = addressId;
            
            const addressLines = this.querySelectorAll('.address-line');
            const displayLines = trigger.querySelectorAll('.address-line');
            
            addressLines.forEach((line, index) => {
                if (displayLines[index]) {
                    displayLines[index].textContent = line.textContent;
                }
            });
            
            modal.style.display = 'none';
        });
    }
    
    // Initialize address selection for existing addresses
    document.querySelectorAll('.address-option').forEach(addressOption => {
        setupAddressSelection(addressOption);
    });
    
    // Toggle new address form
    addNewAddressBtn.addEventListener('click', function(e) {
        e.preventDefault();
        newAddressForm.style.display = newAddressForm.style.display === 'none' ? 'block' : 'none';
    });
    
    // Save new address via AJAX
    document.getElementById('save-address-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch('{{ route("address.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                address: document.getElementById('new-address').value,
                postal_code: document.getElementById('new-postal-code').value,
                phone: document.getElementById('new-phone').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the hidden input
                document.getElementById('selected-address-id').value = data.address.id;
                
                // Update the displayed address
                const trigger = document.getElementById('address-selection-trigger');
                trigger.innerHTML = `
                    <p class="address-line">${data.address.address}</p>
                    <p class="address-line">${data.address.postal_code}</p>
                    <p class="address-line">${data.address.phone}</p>
                    <input type="hidden" name="address_id" id="selected-address-id" value="${data.address.id}">
                `;
                
                // Create new address option
                const newAddressOption = document.createElement('div');
                newAddressOption.className = 'address-option active';
                newAddressOption.setAttribute('data-address-id', data.address.id);
                newAddressOption.innerHTML = `
                    <p class="address-line">${data.address.address}</p>
                    <p class="address-line">${data.address.postal_code}</p>
                    <p class="address-line">${data.address.phone}</p>
                `;
                addressList.appendChild(newAddressOption);
                
                // Set up event listener for the new address
                setupAddressSelection(newAddressOption);
                
                // Close both modal and form
                modal.style.display = 'none';
                newAddressForm.style.display = 'none';
                
                // Reset form
                document.getElementById('save-address-form').reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to save address. Please try again.');
        });
    });
});

    // //
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.getElementById('checkout-form');
        const paymentMethodInput = document.getElementById('payment-method');
        
        // Handle payment method selection
        document.querySelectorAll('input[name="payment_selection"]').forEach(radio => {
            radio.addEventListener('change', function() {
                paymentMethodInput.value = this.value;
            });
        });
        
        // Handle form submission
        checkoutForm.addEventListener('submit', function(e) {
            const selectedPayment = document.querySelector('input[name="payment_selection"]:checked');
            
            if (!selectedPayment) {
                e.preventDefault();
                alert('Please select a payment method');
                return;
            }
            
            if (['stripe', 'fpx'].includes(selectedPayment.value)) {
                e.preventDefault();
                // Get the GRAND TOTAL (subtotal + delivery fee)
                const grandTotal = parseFloat("{{ $grandTotal }}");
                // Include delivery fee in Stripe payment
                window.location.href = "{{ route('stripe.payment') }}?price=" + grandTotal + 
                                    "&payment_method=" + selectedPayment.value;
            }
            // COD will submit normally with the form
        });
    });
</script>

<style>
    .payment-options {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .payment-option input[type="radio"]{
        display: none;
    }
    .payment-option input[type="radio"]:checked + .payment-label {
        border-color: #4CAF50;
        background-color: #f8f9fa;
    }

    .payment-label {
        display: block;
        padding: 1rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .payment-label:hover {
        border-color: #aaa;
    }



    /* Address Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
        position: relative;
    }

    .close-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        position: absolute;
        right: 20px;
        top: 10px;
    }

    .address-list {
        margin: 20px 0;
    }

    .address-option {
        border: 2px solid #ddd;
        padding: 5px;
        padding-left: 20px;
        margin-bottom: 7px;
        border-radius: 5px;
        cursor: pointer;
        position: relative;
    }

    .address-option.active {
        background-color:  #e8f5e9;
        border-left: 6px solid #51ad54;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .address-option:hover {
        background-color: #f5f5f5;
    }

    .address-line {
        margin: 5px 0;
    }

    #address-selection-trigger {
        cursor: pointer;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;

        &:hover{
            border-color: #aaa;
        }
    }

    #address-selection-trigger:hover {
        background-color: #f9f9f9;
    }

    .form-group {
        display: flex;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 30%;
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    #new-address-form {
        margin-top: 10px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 5px;
        border: 2px solid #ddd;
    }


    .total-container2 {
        background: #f1f8e9;
        padding: 15px;
        border-radius: 8px;
        border: 2px solid #ddd;

        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px 20px;
        align-items: center;
    }

    .text-right {
        text-align: right;
    }

    .grand-total-label {
        font-weight: bold;
    }

    .grand-total {
        font-size: 1.2em;
        color: #2e7d32;
        font-weight: bold;
    }
</style>

@endsection