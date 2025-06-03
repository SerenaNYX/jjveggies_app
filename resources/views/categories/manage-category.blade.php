@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="text-center">Manage Categories</h1>
    <a class="btn" style="color: white; margin-bottom: 1rem;" href="{{ route(Auth::guard('employee')->user()->role . '.products.index') }}">&larr; </a>
    <a href="{{ route(Auth::guard('employee')->user()->role . '.categories.create') }}" class="btn">Add New Category</a>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <table class="clean-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $index => $category)
            <tr class="hover-row">
                <td style=" cursor: pointer;" onclick="window.location='{{ route(Auth::guard('employee')->user()->role . '.categories.edit', $category->id) }}'" style="cursor: pointer;">
                    {{ $index + 1 }}</td>
                <td style=" cursor: pointer;" onclick="window.location='{{ route(Auth::guard('employee')->user()->role . '.categories.edit', $category->id) }}'">
                    {{ $category->name }}</td>
                <td style="display: flex; justify-content: flex-end;">
                <!--    <a href="{{ route(Auth::guard('employee')->user()->role . '.categories.edit', $category->id) }}" class="button-edit">Edit</a>-->
                    <form action="{{ route(Auth::guard('employee')->user()->role . '.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button button-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
