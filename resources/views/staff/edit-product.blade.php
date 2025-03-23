@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Edit Product</h1>
    <form id="productForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="description">Product Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" style="resize: none;">{{ $product->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" class="form-control-file">
            @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" height="100">
            @endif
        </div>

        <!-- Product Options -->
        <div class="form-group">
            <label>Product Options</label>
            <div id="options-container">
                @foreach ($product->options as $index => $option)
                <div class="option mb-3">
                    <input type="text" name="options[{{ $index }}][option]" placeholder="Option (e.g., 100g)" class="form-control" value="{{ $option->option }}" required>
                    <input type="number" name="options[{{ $index }}][price]" placeholder="Price" class="form-control" value="{{ $option->price }}" required>
                    <input type="number" name="options[{{ $index }}][quantity]" placeholder="Quantity" class="form-control" value="{{ $option->quantity }}" required>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-option" class="button">Add Option</button>
        </div>

        <button type="submit" class="button">Update Product</button>
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
        div.classList.add('option', 'mb-3');
        div.innerHTML = `
            <input type="text" name="options[${index}][option]" placeholder="Option (e.g., 100g)" class="form-control" required>
            <input type="number" name="options[${index}][price]" placeholder="Price" class="form-control" required>
            <input type="number" name="options[${index}][quantity]" placeholder="Quantity" class="form-control" required>
        `;
        container.appendChild(div);
    });
});
</script>
@endsection