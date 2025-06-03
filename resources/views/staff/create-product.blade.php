@extends('layouts.employee')

@section('content')
<div class="container">
    <a href="{{ route(auth('employee')->user()->role . '.products.index') }}" class="btn back-btn">&larr;</a>
    <div class="product-edit-card">
        <h1 class="text-center">Add New Product</h1>
        <form id="productForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" style="resize: none;"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" class="form-control-file">
            </div>

            <!-- Product Options -->
            <div class="form-group">
                <div id="options-container">
                <label>Product Options</label>
                    <div class="option-container mb-3 form-group">
                        <div class="option-group">
                            <div class="option-field">
                                <label for="option">Option:</label>
                                <input type="text" name="options[0][option]" placeholder="Option (e.g., 100g)" class="form-control" required>
                            </div>
                            <div class="option-field">  
                                <label for="price">Price: RM</label>
                                <input type="number" name="options[0][price]" placeholder="Price" 
                                    class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-option" class="btn btn-success">Add Option</button>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Add Product</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        let actionUrl = '';
        @if (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'admin')
            actionUrl = '{{ route('admin.products.store') }}';
        @elseif (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'staff')
            actionUrl = '{{ route('staff.products.store') }}';
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
                    <label for="option">Option:</label>
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
});
</script>
@endsection
