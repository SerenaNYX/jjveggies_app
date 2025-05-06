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
                <input type="hidden" name="delivery_fee" value="{{ $deliveryFee }}">
                <input type="hidden" name="grand_total" value="{{ $grandTotal }}">
                <input type="hidden" name="address_id" id="selected-address-id" value="{{ $defaultAddress->id ?? '' }}">
                
                @foreach($selectedItems as $itemId)
                    <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                @endforeach
                
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
                
        <!-- Shipping Address Section -->
        <div class="shipping-address-section">
            <h3>Shipping address</h3>
            <div class="selected-address" id="address-selection-trigger" onclick="openAddressModal()">
                @if($defaultAddress)
                    <p class="address-line">{{ $defaultAddress->address }}</p>
                    <p class="address-line">{{ $defaultAddress->postal_code }}</p>
                    <p class="address-line">{{ $defaultAddress->phone }}</p>
                @else
                    <p class="text-danger">Please select a shipping address</p>
                @endif
            </div>
            <input type="hidden" name="address_id" id="selected-address-id" 
                value="{{ $defaultAddress->id ?? '' }}" required>
          <!--  <button type="button" class="btn btn-sm btn-outline-primary mt-2" 
                    onclick="openAddressModal()">Change Address</button>-->
        </div>

        <!-- Voucher Section -->
        <h3>Apply Voucher</h3>
        <div class="voucher-section">
            
            <button type="button" id="select-voucher-btn" class="btn btn-sm btn-primary">Select Voucher</button>
            <div id="voucher-applied" style="display: none;">
                <p>Voucher applied: <span id="applied-voucher-code"></span> (-RM<span id="voucher-discount">0</span>)</p>
                <button type="button" id="remove-voucher-btn" class="btn btn-sm btn-danger">Remove</button>
            </div>
            <div id="voucher-message" class="mt-2"></div>
        </div>

        <!-- Total Price Section -->
        <h3>Payment Details</h3>
        <div class="total-container2">
            <span>Subtotal:</span>
            <span class="text-right">RM{{ number_format($totalPrice, 2) }}</span>
            
            <span>Delivery Fee:</span>
            <span class="text-right">RM6.00</span>

            <span id="discount-row" style="display: none;">Voucher Discount:</span>
            <span id="discount-amount" class="text-right" style="display: none;">-RM0.00</span>
            
            <span class="grand-total-label">Total Payment:</span>
            <span class="grand-total text-right">RM{{ number_format($grandTotal, 2) }}</span>
        </div>


        <input type="hidden" name="delivery_fee" value="{{ $deliveryFee }}">
        <input type="hidden" name="grand_total" value="{{ $grandTotal }}">


  <!--      <div class="total-container">
            <h3>Total Payment: RM {{ number_format($grandTotal, 2) }}</h3>
            <button type="submit" class="btn-checkout">Complete Checkout</button>
        </div>-->

        <div class="total-container">
            <h3>Total Payment: RM <span id="dynamic-grand-total">{{ number_format($grandTotal, 2) }}</span></h3>
            <button type="submit" class="btn-checkout">Complete Checkout</button>
        </div>
    </form>


    <!-- Address Selection Modal -->
    <div class="modal" id="address-modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" onclick="closeAddressModal()">&times;</span>
            <h3>Select Delivery Address</h3>
            
            <button id="add-new-address-btn" class="btn btn-sm btn-primary mb-3" 
                    onclick="toggleAddressForm()">Add New Address</button>
            
            <!-- New Address Form (initially hidden) -->
            <div id="new-address-form" style="display:none;">
                <form id="save-address-form">
                    @csrf
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save Address</button>
                        <button type="button" class="btn btn-secondary" style="margin-left: 1rem;"
                                onclick="toggleAddressForm()">Cancel</button>
                    </div>
                </form>
            </div>

            <div class="address-list">
                @foreach($addresses as $address)
                    <div class="address-option @if($defaultAddress && $address->id == $defaultAddress->id) active @endif" 
                        data-address-id="{{ $address->id }}"
                        onclick="selectAddress(this, {{ $address->id }}, '{{ $address->address }}', '{{ $address->postal_code }}', '{{ $address->phone }}')">
                        <p class="address-line">{{ $address->address }}</p>
                        <p class="address-line">{{ $address->postal_code }}</p>
                        <p class="address-line">{{ $address->phone }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Voucher modal -->
    <div class="modal" id="voucher-modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" onclick="closeVoucherModal()">&times;</span>
            <h3>Select Voucher</h3>
            <div class="voucher-list">
                <div id="available-vouchers-container">
                    <!-- Vouchers will appear here -->
                </div>
            </div>
        </div>
    </div>

    </div>
</div>



<script>
    // Address Modal Functions
    function openAddressModal() {
        document.getElementById('address-modal').style.display = 'block';
    }
    
    function closeAddressModal() {
        document.getElementById('address-modal').style.display = 'none';
        document.getElementById('new-address-form').style.display = 'none';
    }
    
    function toggleAddressForm() {
        const form = document.getElementById('new-address-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
    
    function selectAddress(element, addressId, address, postalCode, phone) {
        // Update selected address display
        const trigger = document.getElementById('address-selection-trigger');
        trigger.innerHTML = `
            <p class="address-line">${address}</p>
            <p class="address-line">${postalCode}</p>
            <p class="address-line">${phone}</p>
        `;
        
        // Update hidden input
        document.getElementById('selected-address-id').value = addressId;
        
        // Highlight selected address in modal
        document.querySelectorAll('.address-option').forEach(opt => {
            opt.classList.remove('active');
        });
        element.classList.add('active');
        
        // Close modal
        closeAddressModal();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize address selection for existing addresses
        document.querySelectorAll('.address-option').forEach(option => {
            option.addEventListener('click', function() {
                const addressId = this.getAttribute('data-address-id');
                const addressLines = this.querySelectorAll('.address-line');
                
                selectAddress(
                    this,
                    addressId,
                    addressLines[0].textContent,
                    addressLines[1].textContent,
                    addressLines[2].textContent
                );
            });
        });
    
        // Save new address via AJAX
        document.getElementById('save-address-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('{{ route("address.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    address: this.elements.address.value,
                    postal_code: this.elements.postal_code.value,
                    phone: this.elements.phone.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload(); // Refresh to show new address
                } else {
                    alert('Error: ' + (data.message || 'Failed to save address'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save address. Please try again.');
            });
        });
    
        // Payment method selection
        const paymentMethodInput = document.getElementById('payment-method');
        document.querySelectorAll('input[name="payment_selection"]').forEach(radio => {
            radio.addEventListener('change', function() {
                paymentMethodInput.value = this.value;
            });
        });
        
        // Form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const selectedPayment = document.querySelector('input[name="payment_selection"]:checked');
    const addressId = document.getElementById('selected-address-id').value;
    const voucherInput = document.querySelector('input[name="voucher_code"]');
    
    if (!selectedPayment) {
        alert('Please select a payment method');
        return;
    }
    
    if (!addressId) {
        alert('Please select a shipping address');
        openAddressModal();
        return;
    }

    // Get the current grand total (which includes any voucher discount)
    const grandTotal = document.querySelector('input[name="grand_total"]').value;
    
    // Create a hidden input for the grand total
    const grandTotalInput = document.createElement('input');
    grandTotalInput.type = 'hidden';
    grandTotalInput.name = 'grand_total';
    grandTotalInput.value = grandTotal;
    this.appendChild(grandTotalInput);
    
    // Add voucher code if exists
    if (voucherInput) {
        const voucherClone = voucherInput.cloneNode();
        this.appendChild(voucherClone);
    }
    
    // Set the payment method value
    document.getElementById('payment-method').value = selectedPayment.value;
    
    // Submit the form
    this.submit();
});
    });
    </script>

<script>
    // Voucher handling
    // Voucher handling functions
function openVoucherModal() {
    fetch('{{ route("vouchers.available") }}')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(vouchers => {
            const container = document.getElementById('available-vouchers-container');
            container.innerHTML = '';
            
            if (vouchers.length === 0) {
                container.innerHTML = '<p>No available vouchers</p>';
                return;
            }
            
            vouchers.forEach(voucher => {
                const discount = Number(voucher.discount_amount);
                const minSpend = Number(voucher.minimum_spend);
                
                const voucherEl = document.createElement('div');
                voucherEl.className = 'voucher-option';
                voucherEl.innerHTML = `
                    <div class="voucher-details">
                        <strong>${voucher.code}</strong>
                        <p>Discount: RM${discount.toFixed(2)}</p>
                        <p>Min. Spend: RM${minSpend.toFixed(2)}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" 
                        onclick="selectVoucher('${voucher.code}', ${discount}, ${minSpend})">
                        Apply
                    </button>
                `;
                container.appendChild(voucherEl);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('available-vouchers-container').innerHTML = 
                '<p>Error loading vouchers. Please try again.</p>';
        });
    
    document.getElementById('voucher-modal').style.display = 'block';
}

function closeVoucherModal() {
    document.getElementById('voucher-modal').style.display = 'none';
}

function selectVoucher(code, discount, minSpend) {
    const grandTotalInput = document.querySelector('input[name="grand_total"]');
    const originalTotal = parseFloat(grandTotalInput.dataset.originalValue || grandTotalInput.value);
    
    // Store original total if not already stored
    if (!grandTotalInput.dataset.originalValue) {
        grandTotalInput.dataset.originalValue = originalTotal.toString();
    }

    // Check minimum spend requirement
    if (originalTotal < minSpend) {
        document.getElementById('voucher-message').textContent = 
            `This voucher requires a minimum spend of RM${minSpend.toFixed(2)}`;
        document.getElementById('voucher-message').className = 'mt-2 text-danger';
        return;
    }
    
    // Calculate new total after discount
    const newTotal = originalTotal - discount;
    
    // Update all displayed totals
    document.querySelector('.grand-total').textContent = 'RM' + newTotal.toFixed(2);
    document.getElementById('dynamic-grand-total').textContent = newTotal.toFixed(2);
    
    // Update UI
    document.getElementById('voucher-message').textContent = 'Voucher applied successfully!';
    document.getElementById('voucher-message').className = 'mt-2 text-success';
    
    document.getElementById('applied-voucher-code').textContent = code;
    document.getElementById('voucher-discount').textContent = discount.toFixed(2);
    
    // Show and update discount row
    document.getElementById('discount-row').style.display = 'block';
    document.getElementById('discount-amount').style.display = 'block';
    document.getElementById('discount-amount').textContent = '-RM' + discount.toFixed(2);
    
    // Update voucher code input (create if doesn't exist)
    let voucherInput = document.querySelector('input[name="voucher_code"]');
    if (!voucherInput) {
        voucherInput = document.createElement('input');
        voucherInput.type = 'hidden';
        voucherInput.name = 'voucher_code';
        document.getElementById('checkout-form').appendChild(voucherInput);
    }
    voucherInput.value = code;
    
    // Update grand total value
    grandTotalInput.value = newTotal;
    
    // Show applied voucher UI
    document.getElementById('voucher-applied').style.display = 'block';
    closeVoucherModal();
}

function removeVoucher() {
    const grandTotalInput = document.querySelector('input[name="grand_total"]');
    const originalTotal = parseFloat(grandTotalInput.dataset.originalValue);
    const currentDiscount = parseFloat(document.getElementById('voucher-discount').textContent) || 0;
    
    // Reset to original total
    const resetTotal = originalTotal;
    document.querySelector('.grand-total').textContent = 'RM' + resetTotal.toFixed(2);
    document.getElementById('dynamic-grand-total').textContent = resetTotal.toFixed(2);
    grandTotalInput.value = resetTotal;
    
    // Hide discount row
    document.getElementById('discount-row').style.display = 'none';
    document.getElementById('discount-amount').style.display = 'none';
    
    // Remove voucher input if exists
    const voucherInput = document.querySelector('input[name="voucher_code"]');
    if (voucherInput) {
        voucherInput.remove();
    }
    
    // Reset voucher UI
    document.getElementById('voucher-applied').style.display = 'none';
    document.getElementById('voucher-message').textContent = 'Voucher removed';
    document.getElementById('voucher-message').className = 'mt-2 text-success';
    document.getElementById('applied-voucher-code').textContent = '';
    document.getElementById('voucher-discount').textContent = '0';
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners
    document.getElementById('select-voucher-btn').addEventListener('click', openVoucherModal);
    document.getElementById('remove-voucher-btn').addEventListener('click', removeVoucher);
    
    // Initialize original grand total value
    const grandTotalInput = document.querySelector('input[name="grand_total"]');
    if (grandTotalInput && !grandTotalInput.dataset.originalValue) {
        grandTotalInput.dataset.originalValue = grandTotalInput.value;
    }
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

    /* Voucher modal */
    /* Add to your styles */
#voucher-modal .modal-content {
    max-width: 500px;
}

.voucher-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    border-radius: 5px;
}

.voucher-option .voucher-details {
    flex: 1;
}

.voucher-option button {
    margin-left: 10px;
}

</style>

@endsection