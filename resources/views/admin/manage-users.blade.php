@extends('layouts.employee')

@section('content')
<div class="container">
    <div class="manage-container">
        <h1 class="manageusers-header text-center">Manage Users</h1>

        <!-- Toggle Buttons -->
        <div class="flex-container">
            <button id="employeeBtn" class="btn active" onclick="showEmployees()">Employees</button>
            <button id="customerBtn" class="btn" onclick="showCustomers()">Customers</button>
            <input type="text" id="searchQuery" class="form-control" placeholder="Search for users..." onkeyup="searchUsers()">
        </div>
    </div>

    <div class="product-edit-card">
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
                        <th style="width: 35%">Email</th>
                        <th style="width: 15%">Role</th>
                        <th style="width: 30%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->role }}</td>
                            <td class="actions-column">
                                @if($employee->name == 'Admin User') <!-- Disable Edit button for "Admin User" -->
                                    <button class="button button-edit" style="background-color: gray; cursor: default; margin-bottom: 4px;" disabled>Edit</button>
                                @else
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="button button-edit" style="margin-bottom: 4px;">Edit</a>
                                @endif
                                @if($employee->name == 'Admin User') <!-- Disable the delete button for Admin User -->
                                    <button class="button button-danger" style="background-color: gray; cursor: default;" disabled>Delete</button>
                                @else
                                    <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button button-danger" onclick="return confirm('Are you sure?')">Delete</button>
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
                        <th style="width: 24%">Email</th>
                        <th>Contact</th>
                    <!--    <th>Status</th>-->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->uid }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}
                                @if ($customer->hasVerifiedEmail())
                                    <small style="color: green;"> (Verified)</small>
                                @else
                                    <small style="color: red;"> (Unverified)</small>
                                @endif
                            </td>
                            <td>{{ $customer->contact }}</td>

                            <td class="actions-column">
                                @if ($customer->banned_at)
                                    <button class="button button-danger2" 
                                            onclick="toggleBan({{ $customer->id }}, 'unban', this)">
                                        Unban
                                    </button>
                                @else
                                    <button class="button button-danger" 
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
             //   const statusCell = row.find('td:nth-child(5)'); // Update to correct column index
                
                // Update the status badge
                if (action === 'ban') {
             //       statusCell.html('<span class="badge badge-danger">Banned</span>');
                    // Replace the button with unban button
                    $(buttonElement).replaceWith(`
                        <button class="button-danger2" 
                                onclick="toggleBan(${userId}, 'unban', this)">
                            Unban
                        </button>
                    `);
                } else {
              //      statusCell.html('<span class="badge badge-success">Active</span>');
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


    @media (min-width: 768px) and (max-width: 1024px) {
        .clean-table td, .clean-table th {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }

        .badge {
            font-size: 11px;
            padding: 5px;
        }
        .clean-table {
            overflow-x: auto;
            font-size: 13px;
        }

        .actions-column .button {
            margin: 3px !important;
            padding: 0;
            width: 70px;
        }

        .button-add {
            font-size: 15px !important;
            line-height: normal;
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

    .clean-table tbody tr {
        display: block;
        width: 100%;
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
    }

    .clean-table td {
        padding: 5px;
        border: none;
        width: 100%;
        display: block;
    }

    .clean-table td::before {
        content: attr(data-label);
        font-weight: bold;
        width: 40%;
    }

    .clean-table .button {
        margin: 3px;
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
