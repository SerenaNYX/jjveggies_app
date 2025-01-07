@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Edit Category</h1>
    <form action="{{ route('staff.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>
        <button type="submit" class="button">Update Category</button>
    </form>
</div>
@endsection
