<!-- resources/views/addresses.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1> Manage Addresses</h1>
    <!-- Add New Address Button -->
    <button id="add-address-button" class="btn btn-primary mt-4" onclick="toggleAddAddressForm()">Add New Address</button>

    <!-- Add New Address Form (Hidden by Default) -->
    <div id="add-address-form" style="display: none;">
        <div class="add-address-container">
            <form action="{{ route('address.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="new_address">Address</label>
                    <input type="text" name="address" id="new_address" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" class="form-control" maxlength="5" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="form-control" maxlength="12" required oninput="validateContact(this)" required>
                    <script>
                        function validateContact(input) {
                            input.value = input.value.replace(/[^0-9]/g, '');
                        }
                    </script>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Address</button>
                    <button type="button" class="btn btn-secondary" onclick="toggleAddAddressForm()">Cancel</button>
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
                                <label for="address-{{ $address->id }}">Address</label>
                                <input type="text" name="address" id="address-{{ $address->id }}" class="form-control" value="{{ $address->address }}" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code-{{ $address->id }}">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code-{{ $address->id }}" class="form-control" value="{{ $address->postal_code }}" maxlength="5" required>
                            </div>
                            <div class="form-group">
                                <label for="phone-{{ $address->id }}">Phone Number</label>
                                <input type="tel" name="phone" id="phone-{{ $address->id }}" class="form-control"  maxlength="12" required oninput="validateContact(this)"
                                       value="{{ $address->phone }}" required>
                                <script>
                                    function validateContact(input) {
                                        input.value = input.value.replace(/[^0-9]/g, '');
                                    }
                                </script>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEditForm({{ $address->id }})">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

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

<!-- Custom CSS for Address Containers -->
<style>

    .back-button {
        display: inline-block;
        font-size: 24px; /* Larger font size for the arrow */
        font-weight: bold; /* Bold text */
        color: #000; /* Black color */
        text-decoration: none; /* Remove underline */
        margin-bottom: 20px; /* Space below the button */
    }

    .back-button:hover {
        color: #63966b; /* Change color on hover */
    }
    .address-container {
        border: 1px solid #ddd; /* Light gray border */
        border-radius: 8px; /* Rounded corners */
        padding: 15px; /* Inner spacing */
        margin-bottom: 15px; /* Space between address containers */
        background-color: #f9f9f9; /* Light background color */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    .address-container .address-content {
        display: flex;
        flex-direction: column;
        gap: 10px; /* Space between elements inside the container */
    }

    .address-container .btn {
        margin-right: 10px; /* Space between buttons */
    }

    .address-container .form-group {
        margin-bottom: 10px; /* Space between form fields */
    }

    .address-container .card-text {
        margin: 0; /* Remove default margin for cleaner look */
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

    /* Custom CSS for Add New Address Section */
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

    .add-address-container .form-actions {
        display: flex; /* Align buttons horizontally */
        gap: 10px; /* Space between buttons */
        margin-top: 20px; /* Space above buttons */
    }

    .add-address-container .form-actions .btn {
        flex: 1; /* Equal width for buttons */
    }
</style>
@endsection