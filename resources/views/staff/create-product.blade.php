@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Add New Product</h1>
    <form action="{{ route('staff.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Product Price</label>
            <input type="text" id="price" name="price" class="form-control" required>
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
        <button type="submit" class="button">Add Product</button>
    </form>
</div>
@endsection
