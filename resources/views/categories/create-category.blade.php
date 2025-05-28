@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h1>
    <a class="btn" style="color: white; margin-bottom: 1rem;" href="{{ route(Auth::guard('employee')->user()->role . '.categories.index') }}">&larr; </a>
    
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
@endsection
