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

        <table class="clean-table table-striped" id="employeeTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 30%">Actions</th>
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
                                <button class="button-edit" style="background-color: gray; cursor: default;" disabled>Edit</button>
                            @else
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="button-edit">Edit</a>
                            @endif
                            @if($employee->name == 'Admin User') <!-- Disable the delete button for Admin User -->
                                <button class="button-danger" style="background-color: gray; cursor: default;" disabled>Delete</button>
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
        <table class="clean-table table-striped" id="customerTable">
            <thead>
                <tr>
                    <th>UID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th style="width: 15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->uid }}</td>
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
                                <button class="button-danger2" 
                                        onclick="toggleBan({{ $customer->id }}, 'unban', this)">
                                    Unban
                                </button>
                            @else
                                <button class="button-danger" 
                                        onclick="toggleBan({{ $customer->id }}, 'ban', this)">
                                    Ban
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<script>
// Add CSRF token to AJAX headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function toggleBan(userId, action, buttonElement) {
    if (!confirm(`Are you sure you want to ${action} this customer?`)) {
        return;
    }

    const url = action === 'ban' 
        ? `/admin/customers/${userId}/deny` 
        : `/admin/customers/${userId}/unban`;

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Update the status cell
                const row = $(buttonElement).closest('tr');
                const statusCell = row.find('td:nth-child(5)'); // Update to correct column index
                
                // Update the status badge
                if (action === 'ban') {
                    statusCell.html('<span class="badge badge-danger">Banned</span>');
                    // Replace the button with unban button
                    $(buttonElement).replaceWith(`
                        <button class="button-danger2" 
                                onclick="toggleBan(${userId}, 'unban', this)">
                            Unban
                        </button>
                    `);
                } else {
                    statusCell.html('<span class="badge badge-success">Active</span>');
                    // Replace the button with ban button
                    $(buttonElement).replaceWith(`
                        <button class="button-danger" 
                                onclick="toggleBan(${userId}, 'ban', this)">
                            Ban
                        </button>
                    `);
                }
                
                // Show success message
                alert(`Customer ${action === 'ban' ? 'banned' : 'unbanned'} successfully!`);
            }
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        }
    });
}
</script>

<script>
    // Prevent double form submission
function confirmAction(action) {
    if (confirm(`Are you sure you want to ${action} this customer?`)) {
        // Disable the button to prevent double submission
        event.target.disabled = true;
        event.target.form.submit();
        return true;
    }
    return false;
}

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

<style>
    .badge-success {
        background-color: #a2cb97;;
        border-radius: 18px;
        padding: 0.3rem 0.8rem 0.3rem 0.8rem;
    }

    .badge-danger {
        background-color: #da7d7d;;
        border-radius: 18px;
        padding: 0.3rem 0.8rem 0.3rem 0.8rem;
    }
    
    .button-edit {
        width: 120px; 
        height: 35px;
        border: none !important;
        background-color: #73a26c;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-family: 'Roboto', Arial, sans-serif;
        font-size: 14px;

        &:hover {
            background-color: #587d53;
        }
    }

    /* Mobile User Management Styles */
@media (max-width: 768px) {

    .manageusers-header {
        font-size: 24px;
        margin-bottom: 15px;
    }

    /* Toggle Buttons */
    .flex-container {
        flex-direction: column;
        gap: 10px;
        margin-bottom: 15px;
    }

    #employeeBtn, #customerBtn {
        width: 100%;
        padding: 10px;
        font-size: 16px;
    }

    #searchQuery {
        width: 100%;
        padding: 10px;
        font-size: 14px;
    }

    .clean-table td::before {
        content: attr(data-label);
        font-weight: bold;
        width: 40%;
    }

    /* Buttons */
    .button-add {
        width: 100%;
        text-align: center;
        margin-bottom: 15px;
    }

    .button-edit, 
    .button-danger,
    .button-danger2 {
        padding: 6px 10px;
        font-size: 14px;
    }

    /* Badges */
    .badge {
        padding: 4px 8px;
        font-size: 12px;
    }

    /* Section headers */
    h2 {
        font-size: 20px;
        margin-bottom: 15px;
    }

    /* Alerts */
    .alert-success {
        padding: 10px;
        font-size: 14px;
        margin-bottom: 15px;
    }
}

/* For very small screens */
@media (max-width: 480px) {
    .manageusers-header {
        font-size: 22px;
    }

    .clean-table td {
        font-size: 14px;
    }

    .button-edit, 
    .button-danger,
    .button-danger2 {
        padding: 5px 8px;
        font-size: 13px;
    }
}
</style>

@endsection
