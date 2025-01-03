@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Edit Product</h1>
    <form action="{{ route('staff.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label for="price">Product Price</label>
            <input type="text" id="price" name="price" class="form-control" value="{{ $product->price }}" required>
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
            <textarea id="description" name="description" class="form-control" rows="4" style="resize: none;" required>{{ $product->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" class="form-control-file">
            @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" height="100">
            @endif
        </div>
        <button type="submit" class="button">Update Product</button>
    </form>
</div>
@endsection
