@extends('layouts.employee')

@section('content')
<div class="container">
    <a href="{{ route(auth('employee')->user()->role . '.employees.index') }}" class="btn back-btn">&larr;</a>
    <div class="product-edit-card">
        <h1>Edit Employee</h1>
        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ $employee->name }}" required>
            </div>
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="{{ $employee->email }}" required>
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
                <small>Leave blank to keep the current password.</small>
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
            </div>
            <div>
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-control" id="role" required>
                    <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="staff" {{ $employee->role == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="driver" {{ $employee->role == 'driver' ? 'selected' : '' }}>Driver</option>
                </select>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
