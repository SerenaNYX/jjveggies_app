@extends('layouts.employee')

@section('content')
<div class="container">
    <h1 class="manageusers-header text-center">Manage Users</h1>

    <!-- Toggle Buttons -->
    <div class="flex-container">
        <button id="employeeBtn" class="btn active" onclick="showEmployees()">Employees</button>
        <button id="customerBtn" class="btn" onclick="showCustomers()">Customers</button>
        <input type="text" id="searchQuery" class="form-control" placeholder="Search for users..." onkeyup="searchUsers()">
    </div>

    <!-- Employee Table (Initially Visible) -->
    <div id="employeesSection">
        <h2>Employees</h2>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="flex-container">
            <a href="{{ route('admin.employees.create') }}" class="button-add">Add New Employee</a>
        </div>

        <table class="table table-striped table-product" id="employeeTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="actions-column2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->role }}</td>
                        <td>
                            @if($employee->name == 'Admin User') <!-- Disable Edit button for "Admin User" -->
                                <button class="button-edit" style="background-color: gray" disabled>Edit</button>
                            @else
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="button-edit">Edit</a>
                            @endif
                            @if($employee->name == 'Admin User') <!-- Disable the delete button for Admin User -->
                                <button class="button-danger" style="background-color: gray" disabled>Delete</button>
                            @else
                                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Customer Table (Initially Hidden) -->
    <div id="customersSection" style="display: none;">
        <h2>Customers</h2>
        <table class="table table-striped table-product" id="customerTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
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

</div>

<script>
// Function to show Employees section
function showEmployees() {
    document.getElementById('employeesSection').style.display = 'block';
    document.getElementById('customersSection').style.display = 'none';
    document.getElementById('employeeBtn').classList.add('active');
    document.getElementById('customerBtn').classList.remove('active');
}

// Function to show Customers section
function showCustomers() {
    document.getElementById('employeesSection').style.display = 'none';
    document.getElementById('customersSection').style.display = 'block';
    document.getElementById('customerBtn').classList.add('active');
    document.getElementById('employeeBtn').classList.remove('active');
}

// Function to search users (employees and customers)
function searchUsers() {
    var query = document.getElementById('searchQuery').value.toLowerCase();

    // Filter employees table
    var employeeRows = document.getElementById('employeeTable').getElementsByTagName('tr');
    for (var i = 1; i < employeeRows.length; i++) {
        var name = employeeRows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
        var email = employeeRows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
        
        if (name.includes(query) || email.includes(query)) {
            employeeRows[i].style.display = '';
        } else {
            employeeRows[i].style.display = 'none';
        }
    }

    // Filter customers table
    var customerRows = document.getElementById('customerTable').getElementsByTagName('tr');
    for (var i = 1; i < customerRows.length; i++) {
        var name = customerRows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
        var email = customerRows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
        var contact = customerRows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
        var address = customerRows[i].getElementsByTagName('td')[3].textContent.toLowerCase();
        
        if (name.includes(query) || email.includes(query) || contact.includes(query) || address.includes(query)) {
            customerRows[i].style.display = '';
        } else {
            customerRows[i].style.display = 'none';
        }
    }
}
</script>

@endsection
