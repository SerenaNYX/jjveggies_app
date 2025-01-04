@extends('layouts.employee')

@section('content')
<div class="container">
    <h1>Manage Users</h1>

    <h2>Employees</h2>

    <div class="flex-container">
        <a href="{{ route('admin.employees.create') }}" class="button-add">Add New Employee</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
    <table class="table table-striped table-product">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->role }}</td>
                    <td>
                        <a href="{{ route('admin.employees.edit', $employee) }}" class="button-edit">Edit</a>
                        <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Customers</h2>
    <table class="table table-striped table-product">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->contact }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>
                        @if ($customer->banned_at)
                            <span class="badge badge-danger">Banned</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
                    </td>
                    <td>
                        @if ($customer->banned_at)
                            <!-- Unban Button -->
                            <form action="{{ route('admin.customers.unban', $customer) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="button-danger2" onclick="return confirm('Are you sure you want to unban this customer?')">Unban</button>
                            </form>
                        @else
                            <!-- Ban Button -->
                            <form action="{{ route('admin.customers.deny', $customer) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="button-danger" onclick="return confirm('Are you sure you want to ban this customer?')">Ban</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
