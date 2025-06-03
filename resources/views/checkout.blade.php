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
        <table class="clean-table">
            <thead>
                <tr>
                    <th></th>
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
                        <td width="15%">
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" width="65" height="65" style="object-fit: scale-down;">
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
                        <input type="radio" id="stripe-payment" name="payment_selection" value="card" class="hidden">
                        <label for="stripe-payment" class="payment-label">
                            <div class="payment-content">
                              <!--  <span>Credit/Debit Card</span>-->
                                <img src="{{ asset('img/stripe.png') }}" alt="Card" height="30">
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
            <div id="voucher-applied" style="display: none; border: 2px solid #ddd;">
                <p>Voucher applied: <span id="applied-voucher-code"></span><br> <strong>Discount RM<span id="voucher-discount">0</span></strong></p>
                <button type="button" id="remove-voucher-btn" class="btn btn-sm btn-danger">Remove</button>
            </div>
            <div id="voucher-message"></div>
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
                        <input type="text" name="postal_code" class="form-control" maxlength="5" oninput="validateContact(this)" required>
                        
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" maxlength="12" oninput="validateContact(this)" required>
                        <script>
                            function validateContact(input) {
                                input.value = input.value.replace(/[^0-9]/g, '');
                            }
                        </script>
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
<!-- Add this script section to your checkout.blade.php -->
<script>
// Valid postcodes for the specified areas
const validPostcodes = [
    '81750', // Permas Jaya
    '81100', // Austin Heights
    '81300', // Skudai
    '79100'  // Iskandar Puteri
];

// Also allow any postcode between 80000-81300 for general Johor Bahru area
function isValidPostcode(postcode) {
    // Check if it's one of the specific valid postcodes
    if (validPostcodes.includes(postcode)) {
        return true;
    }
    
    // Check if it's in the Johor Bahru range (80000-81300)
    const numericPostcode = parseInt(postcode);
    if (numericPostcode >= 80000 && numericPostcode <= 81300) {
        return true;
    }
    
    return false;
}

// Validate postcode in the address form
document.addEventListener('DOMContentLoaded', function() {
    const addressForm = document.getElementById('save-address-form');
    if (addressForm) {
        const postcodeInput = addressForm.querySelector('input[name="postal_code"]');
        
        // Add validation on form submission
        addressForm.addEventListener('submit', function(e) {
            const postcode = postcodeInput.value.trim();
            
            if (!isValidPostcode(postcode)) {
                e.preventDefault();
                postcodeInput.classList.add('is-invalid');
                
                // Create error message if it doesn't exist
                let errorElement = postcodeInput.nextElementSibling;
                if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    postcodeInput.parentNode.insertBefore(errorElement, postcodeInput.nextSibling);
                }
                
                errorElement.textContent = 
                    'Invalid postcode. We only deliver to Permas Jaya, Johor Bahru, Austin Heights, Skudai, and Iskandar Puteri.';
                return false;
            }
            
            return true;
        });
        
        // Validate postcode on input change
        postcodeInput.addEventListener('input', function() {
            const postcode = this.value.trim();
            
            if (postcode.length === 5) {
                if (isValidPostcode(postcode)) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    
                    // Remove error message if exists
                    const errorElement = this.nextElementSibling;
                    if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                        errorElement.textContent = '';
                    }
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    
                    // Create or update error message
                    let errorElement = this.nextElementSibling;
                    if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
                        errorElement = document.createElement('div');
                        errorElement.className = 'invalid-feedback';
                        this.parentNode.insertBefore(errorElement, this.nextSibling);
                    }
                    
                    errorElement.textContent = 
                        'Invalid postcode. We only deliver to Permas Jaya (81750), Johor Bahru (80000-81300), Austin Heights (81100), Skudai (81300), and Iskandar Puteri (79100).';
                }
            } else {
                this.classList.remove('is-valid');
                this.classList.remove('is-invalid');
                
                // Remove error message if exists
                const errorElement = this.nextElementSibling;
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.textContent = '';
                }
            }
        });
    }
});
</script>

<!-- Add this style to your existing styles -->
<style>
/* Postcode validation styles */
.is-valid {
    border-color: #28a745;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #dc3545;
    position: static;
}


</style>
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
        document.getElementById('voucher-message').className = 'text-danger';
        return;
    }
    
    // Calculate new total after discount
    const newTotal = originalTotal - discount;
    
    // Update all displayed totals
    document.querySelector('.grand-total').textContent = 'RM' + newTotal.toFixed(2);
    document.getElementById('dynamic-grand-total').textContent = newTotal.toFixed(2);
    
    // Update UI
    //document.getElementById('voucher-message').textContent = 'Voucher applied successfully!';
    document.getElementById('voucher-message').className = 'text-success';
    
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
    //document.getElementById('voucher-message').textContent = 'Voucher removed';
    //document.getElementById('voucher-message').className = 'text-success';
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

    .clean-table img {
        width: min-content;
    }
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
        margin-bottom: 15px;
        display: block;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        width: auto;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #9e9e9e;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 5px; /* Add space between input and error message */
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

    .btn-danger {
        background-color: #d9534f;
        &:hover {
            background-color: #c9302c;
        }
    }

    /* Voucher modal */
    
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



<style>
    /* Mobile Checkout Styles */
@media (max-width: 768px) {

    /* Table styling */
    .clean-table {
        width: 100%;
        margin-bottom: 20px;
    }

    .clean-table thead {
        display: none;
    }

    .clean-table tbody tr {
        display: flex;
        flex-wrap: wrap;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .clean-table tbody tr:last-child {
        border-bottom: none;
    }

    .clean-table td {
        padding: 5px;
        width: 48%;
        display: flex;
        align-items: center;
    }

    /* Specific column styling */
    .clean-table td:nth-child(1) { /* Image */
        width: 30%;
        justify-content: center;
    }

    .clean-table td:nth-child(2) { /* Product name */
        width: 70%;
        font-weight: bold;
    }

    .clean-table td:nth-child(3), td:nth-child(5) {
        margin-right: 4%;
    }

    .clean-table td:nth-child(3)::before {
        content: "Option: ";
        color: #666;
        margin-right: 5px;
    }

    .clean-table td:nth-child(4)::before {
        content: "Qty: ";
        color: #666;
        margin-right: 5px;
    }

    .clean-table td:nth-child(5)::before {
        content: "Price: ";
        color: #666;
        margin-right: 5px;
    }

    .clean-table td:nth-child(6)::before {
        content: "Subtotal: ";
        color: #666;
        margin-right: 5px;
        font-weight: bold;
    }

    .clean-table img {
        width: 60px;
        height: 60px;
        object-fit: scale-down;
    }

    /* Payment methods */
    .payment-methods h3,
    .shipping-address-section h3,
    .voucher-section h3,
    .total-container2 h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }
/*
    .payment-options {
        flex-direction: column;
        gap: 10px;
    }*/

    .payment-label {
        padding: 12px;
    }

    /* Address section */
    .shipping-address-section {
        margin: 20px 0;
    }

    #address-selection-trigger {
        padding: 12px;
    }

    /* Voucher section */
    .voucher-section {
        margin: 20px 0;
    }

    #select-voucher-btn,
    #remove-voucher-btn {
        padding: 8px 12px;
        font-size: 14px;
    }

    /* Totals section */
    .total-container2 {
        padding: 12px;
        grid-template-columns: 1fr auto;
        gap: 8px 15px;
        margin: 20px 0;
    }

    .grand-total {
        font-size: 1.1em;
    }

    /* Checkout button */
    .total-container {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #5f916a;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
    }

    .total-container h3 {
        font-size: 16px;
        margin: 0;
        color: white;
    }

    .btn-checkout {
        padding: 10px 20px;
        font-size: 16px;
        background: white;
        color: #5f916a;
    }

    /* Modal adjustments */
    .modal-content {
        width: 90%;
        margin: 20% auto;
        padding: 15px;
    }

    .address-option {
        padding: 10px;
        margin-bottom: 8px;
    }

    .voucher-option {
        flex-direction: column;
        align-items: flex-start;
    }

    .voucher-option button {
        margin-left: 0;
        margin-top: 8px;
        width: 100%;
    }

    /* Form elements */
    .form-group {
        margin-bottom: 12px;
    }

    .form-group label {
        width: 50%;
        margin-bottom: 5px;
    }

    #new-address-form {
        padding: 12px;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    .clean-table td {
        font-size: 14px;
    }

    .clean-table td:nth-child(1) {
        width: 25%;
    }

    .clean-table td:nth-child(2) {
        width: 75%;
    }

    .clean-table img {
        width: 50px;
        height: 50px;
        object-fit: scale-down;
    }

    .payment-label {
        padding: 10px;
    }

    .total-container2 {
        font-size: 14px;
    }

    .total-container h3 {
        font-size: 15px;
    }

    .btn-checkout {
        padding: 8px 15px;
        font-size: 15px;
    }
}
</style>
@endsection