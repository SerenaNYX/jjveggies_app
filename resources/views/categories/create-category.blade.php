@extends('layouts.employee')

@section('content')
<div class="container">
    <a class="btn back-btn" style="color: white;" href="{{ route(Auth::guard('employee')->user()->role . '.categories.index') }}">&larr; </a>
    <div class="product-edit-card">
        <h1 class="text-center">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h1>
        
        <form action="{{ isset($category) ? route(Auth::guard('employee')->user()->role . '.categories.update', $category->id) : route(Auth::guard('employee')->user()->role . '.categories.store') }}" method="POST">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ isset($category) ? $category->name : '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }} Category</button>  
        </form>
    </div>
</div>
@endsection
