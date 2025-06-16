<!-- resources/views/addresses.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center"> Manage Addresses</h1>
    <p class="section-description text-center">We deliver to 81750 (Permas Jaya), 80000-81300 (JB), 81100 (Austin Heights), 81300 (Skudai), and 79100 (Iskandar Puteri).</p>
    <!-- Add New Address Button -->
    <div class="button-container">
        <a href="{{ route('profile.edit') }}" class="btn back-button" style="color: white;">
            &larr; 
        </a> 
        <button id="add-address-button" class="btn" onclick="toggleAddAddressForm()">Add New Address</button>
    </div>
    
    <!-- Add New Address Form -->
    <div id="add-address-form" style="display: none;">
        <div class="add-address-container">
            <form action="{{ route('address.store') }}" method="POST" id="address-form">
                @csrf
                <div class="form-group">
                    <label for="new_address">Address <span class="required-asterisk">*</span></label>
                    <input type="text" name="address" id="new_address" class="form-control" required>
                    <small id="address-help" class="form-text text-muted">Please enter an address in Permas Jaya, Johor Bahru, Austin Heights, Skudai, or Iskandar Puteri</small>
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal Code <span class="required-asterisk">*</span></label>
                    <input type="text" name="postal_code" id="postal_code" class="form-control" maxlength="5" oninput="validateContact(this)" required>
                    <small id="postcode-help" class="form-text text-muted">Valid postcodes: 81750 (Permas Jaya), 80000-81300 (JB), 81100 (Austin Heights), 81300 (Skudai), 79100 (Iskandar Puteri)</small>
                    <div id="postcode-error" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number <span class="required-asterisk">*</span></label>
                    <input type="tel" name="phone" id="phone" class="form-control" maxlength="12" required oninput="validateContact(this)" required>
                    <script>
                        function validateContact(input) {
                            input.value = input.value.replace(/[^0-9]/g, '');
                        }
                    </script>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn">Add Address</button>
                    <button type="button" class="btn" onclick="toggleAddAddressForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- List of Addresses -->
    <div class="address-list">
        <br>
        @foreach ($user->addresses as $address)
            <!-- Each Address is Enclosed in a Styled Container -->
            <div class="address-container" id="address-{{ $address->id }}-container">
                <div class="address-content">
                    <!-- Display Address -->
                    <div id="address-{{ $address->id }}-view">
                        <p class="card-text">
                            <strong>Address:</strong> <span id="address-{{ $address->id }}-text">{{ $address->address }}</span><br>
                            <strong>Postal Code:</strong> <span id="postal_code-{{ $address->id }}-text">{{ $address->postal_code }}</span><br>
                            <strong>Phone:</strong> <span id="phone-{{ $address->id }}-text">{{ $address->phone }}</span>
                        </p>
                        <!-- Edit Button -->
                        <button class="btn" onclick="toggleEditForm({{ $address->id }})">Edit</button>
                        <!-- Delete Address Form -->
                        <form action="{{ route('address.destroy', $address->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn btn " onclick="return confirm('Are you sure you want to delete this address?')">Delete</button>
                        </form>
                    </div>

                    <!-- Edit Address Form (Hidden by Default) -->
                    <div id="address-{{ $address->id }}-edit" style="display: none;">
                        <form action="{{ route('address.update', $address->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="address-{{ $address->id }}">Address <span class="required-asterisk">*</span></label>
                                <input type="text" name="address" id="address-{{ $address->id }}" class="form-control" value="{{ $address->address }}" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code-{{ $address->id }}">Postal Code <span class="required-asterisk">*</span></label>
                                <input type="text" name="postal_code" id="postal_code-{{ $address->id }}" class="form-control" value="{{ $address->postal_code }}" maxlength="5" oninput="validateContact(this)" required>
                            </div>
                            <div class="form-group">
                                <label for="phone-{{ $address->id }}">Phone Number <span class="required-asterisk">*</span></label>
                                <input type="tel" name="phone" id="phone-{{ $address->id }}" class="form-control"  maxlength="12" required oninput="validateContact(this)"
                                       value="{{ $address->phone }}" required>
                                <script>
                                    function validateContact(input) {
                                        input.value = input.value.replace(/[^0-9]/g, '');
                                    }
                                </script>
                            </div>
                            <button type="submit" class="btn">Save</button>
                            <button type="button" class="btn" onclick="toggleEditForm({{ $address->id }})">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

<script>
// Valid postcodes for the specified areas
const validPostcodes = ['81750', '81100', '81300', '79100'];

// Check if postcode is valid
function isValidPostcode(postcode) {
    if (validPostcodes.includes(postcode)) return true;
    const numericPostcode = parseInt(postcode);
    return numericPostcode >= 80000 && numericPostcode <= 81300;
}

// Initialize postcode validation for any form
function setupPostcodeValidation(form) {
    const postcodeInput = form.querySelector('input[name="postal_code"]');
    if (!postcodeInput) return;

    // Ensure error element exists
    let errorElement = postcodeInput.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
        errorElement = document.createElement('div');
        errorElement.className = 'invalid-feedback';
        postcodeInput.parentNode.insertBefore(errorElement, postcodeInput.nextSibling);
    }

    // Validate on submission
    form.addEventListener('submit', function(e) {
        const postcode = postcodeInput.value.trim();
        if (!isValidPostcode(postcode)) {
            e.preventDefault();
            postcodeInput.classList.add('is-invalid');
            errorElement.textContent = 'Invalid postcode. We only deliver to Permas Jaya, Johor Bahru, Austin Heights, Skudai, and Iskandar Puteri.';
        }
    });

    // Validate on input
    postcodeInput.addEventListener('input', function() {
        const postcode = this.value.trim();
        if (postcode.length === 5) {
            if (isValidPostcode(postcode)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                errorElement.textContent = '';
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                errorElement.textContent = 'Invalid postcode. We only deliver to Permas Jaya, Johor Bahru, Austin Heights, Skudai, and Iskandar Puteri.';
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
            errorElement.textContent = '';
        }
    });
}

// Initialize all forms on page load
document.addEventListener('DOMContentLoaded', function() {
    // Main add form
    const addForm = document.getElementById('address-form');
    if (addForm) setupPostcodeValidation(addForm);

    // All edit forms
    document.querySelectorAll('[id^="address-"][id$="-edit"] form').forEach(form => {
        setupPostcodeValidation(form);
    });
});

// Modified toggle function to handle edit forms
function toggleEditForm(addressId) {
    const viewDiv = document.getElementById(`address-${addressId}-view`);
    const editDiv = document.getElementById(`address-${addressId}-edit`);

    if (viewDiv.style.display === 'none') {
        viewDiv.style.display = 'block';
        editDiv.style.display = 'none';
    } else {
        viewDiv.style.display = 'none';
        editDiv.style.display = 'block';
    }
}

// Toggle add address form
function toggleAddAddressForm() {
    const form = document.getElementById('add-address-form');
    const button = document.getElementById('add-address-button');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    button.style.display = button.style.display === 'none' ? 'block' : 'none';
}
</script>

<!-- JavaScript to Toggle Edit Form -->
<script>
    function toggleEditForm(addressId) {
        const viewDiv = document.getElementById(`address-${addressId}-view`);
        const editDiv = document.getElementById(`address-${addressId}-edit`);

        if (viewDiv.style.display === 'none') {
            viewDiv.style.display = 'block';
            editDiv.style.display = 'none';
        } else {
            viewDiv.style.display = 'none';
            editDiv.style.display = 'block';
        }
    }

    // JavaScript to Toggle Add Address Form and Button
    function toggleAddAddressForm() {
        const addAddressForm = document.getElementById('add-address-form');
        const addAddressButton = document.getElementById('add-address-button');

        if (addAddressForm.style.display === 'none') {
            addAddressForm.style.display = 'block';
            addAddressButton.style.display = 'none'; // Hide the button
        } else {
            addAddressForm.style.display = 'none';
            addAddressButton.style.display = 'block'; // Show the button
        }
    }
</script>

<style>
    .required-asterisk {
        color: red;
    }

    .button-container {
        display: flex;
        align-items: center;
        gap: 10px;
   
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
    }

    .address-container {
        border: 1px solid #ddd; 
        border-radius: 8px; 
        padding: 15px;
        margin-bottom: 15px;
        background-color: #f9f9f9; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .address-container .address-content {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .address-container .btn {
        margin-right: 10px;
    }

    .address-container .form-group {
        margin-bottom: 10px; 
    }

    .address-container .card-text {
        margin: 0; 
    }

    .address-container:hover {
        border-color: #63966b; /* Change border color on hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Increase shadow on hover */
        transition: all 0.3s ease; /* Smooth transition */
    }

    .delete-btn {
        background-color: #cb4e4e;
        &:hover{
            background-color: #bf3232;
        }
    }

    /* Add New Address Section */
    .add-address-container {
        border: 1px solid #ddd; /* Light gray border */
        border-radius: 8px; /* Rounded corners */
        padding: 20px; /* Inner spacing */
        background-color: #f9f9f9; /* Light background color */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        max-width: 100%; /* Limit width for better readability */
        margin-top: 20px; /* Space above the form */
    }

    .add-address-container h3 {
        margin-bottom: 20px; /* Space below the heading */
    }

    .add-address-container .form-group {
        margin-bottom: 15px; /* Space between form fields */
    }

    .add-address-container .form-group label {
        display: block; /* Make labels block-level for better alignment */
        margin-bottom: 5px; /* Space between label and input */
        font-weight: bold; /* Bold labels */
    }

    .add-address-container .form-group input {
        width: 100%; /* Full-width inputs */
        padding: 8px; /* Padding for inputs */
        border: 1px solid #ccc; /* Light gray border */
        border-radius: 4px; /* Rounded corners */
    }

</style>

<style>
/* Add some visual feedback for valid/invalid postcodes */
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
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #dc3545;
}

.is-invalid ~ .invalid-feedback {
    display: block;
}
</style>
@endsection