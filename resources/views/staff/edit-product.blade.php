@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Edit Product</h1>
    <form id="productForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name" style="font-weight: bold;">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label for="category_id" style="font-weight: bold;">Category</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="description" style="font-weight: bold;">Product Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" style="resize: none;">{{ $product->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="image" style="font-weight: bold;">Product Image</label>
            <input type="file" id="image" name="image" class="form-control-file">
            @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" height="100">
            @endif
        </div>

        <!-- Product Options -->
        <div class="form-group">
            <label style="font-weight: bold;">Product Options</label>
            <label style="color:red; text-align: right;">"UPDATE PRODUCT" to confirm delete/add</label>
            <div id="options-container">
                @foreach ($product->options as $index => $option)
                <div class="option-container mb-3 form-group">
                    <div class="option-group">
                        <div class="option-field">
                            <label for="option">Option #{{ $index + 1 }}:</label>
                            <input type="text" name="options[{{ $index }}][option]" placeholder="Option (e.g., 100g)" class="form-control" value="{{ $option->option }}" required>
                        </div>
                        <div class="option-field">
                            <label for="price">Price: RM</label>
                            <input type="number" name="options[{{ $index }}][price]" placeholder="Price" class="form-control" value="{{ $option->price }}" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger delete-option">Delete</button>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-option" class="btn btn-success">Add Option</button>
        </div>

        <button type="submit" class="button">Update Product</button> 
        <button class="button" href="/admin/products">Cancel</button>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        let actionUrl = '';
        @if (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'admin')
            actionUrl = '{{ route('admin.products.update', $product->id) }}';
        @elseif (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'staff')
            actionUrl = '{{ route('staff.products.update', $product->id) }}';
        @endif

        form.action = actionUrl;
        form.submit();
    });

    // Add option dynamically
    document.getElementById('add-option').addEventListener('click', function() {
        const container = document.getElementById('options-container');
        const index = container.children.length;
        const div = document.createElement('div');
        div.classList.add('option-container', 'mb-3');
        div.innerHTML = `
            <div class="option-group">
                <div class="option-field">
                    <label for="option">Option #${index + 1}:</label>
                    <input type="text" name="options[${index}][option]" placeholder="Option (e.g., 100g)" class="form-control" required>
                </div>
                <div class="option-field">
                    <label for="price">Price: RM</label>
                    <input type="number" name="options[${index}][price]" placeholder="Price" class="form-control" required>
                </div>
            </div>
            <button type="button" class="btn btn-secondary cancel-option">Cancel</button>
        `;
        container.appendChild(div);

        // Add event listener for the Cancel button
        div.querySelector('.cancel-option').addEventListener('click', function() {
            div.remove(); // Remove the dynamically added option form
        });
    });

    document.querySelectorAll('.delete-option').forEach(button => {
    button.addEventListener('click', function() {
        // Confirmation pop-up
        const isConfirmed = confirm('Are you sure you want to delete this option?');
        if (!isConfirmed) {
            return; // Stop if the user cancels
        }

        const optionDiv = this.closest('.option');
        const optionId = optionDiv.dataset.optionId;
        if (optionId) {
            fetch(`/staff/products/options/${optionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    optionDiv.remove();
                }
            });
        } else {
            optionDiv.remove();
        }
    });
});
});

</script>
@endsection