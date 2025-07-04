<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .dashboard-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #44684a;
            color: white;
            padding: 1rem;
            width: calc(100% - 200px); /* Subtract sidebar width */
            margin-left: 200px; /* Align next to sidebar */
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .dashboard-container.full-width {
            margin-left: 0;
            width: 100%;
        }

        .employee-sidebar {
            background-color: #63966b;
            color: white;
            padding: 1rem;
            width: 200px;
            position: fixed;
            height: 100%;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .employee-sidebar.hide {
            transform: translateX(-100%);
        }

        .employee-sidebar .jj-logo {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            margin-bottom: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .employee-sidebar ul {
            margin-top: 50px;
            list-style-type: none;
            padding: 0;
            flex-grow: 1; /* is this needed? */
        }

        .employee-sidebar ul li {
            margin: 1rem 0;
        }

        .employee-sidebar ul li a {
            color: white;
            text-decoration: none;
            &:hover {
                color: #d9ffae;
            }
        }

        .employee-sidebar ul li .logout-button {
            padding: 10px 30px;
            font-size: 16px;
            color: #589a4b;
            background-color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;

            &:hover {
                background-color: #e6ffca;
            }
        }

        .employee-content {
            flex-grow: 1;
            padding: 2rem;
            background-color: #ecf0f1;
            margin-left: 200px; /* Align next to sidebar */
            transition: margin-left 0.3s ease;
        }

        .employee-content.full-width {
            margin-left: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .toggle-button {
            position: fixed;
            top: 1rem;
            left: 1rem;
            color: black;
            padding: 0.5rem 1rem;
            border: 1px solid #6d7e59;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1001;
            &:hover {
                background-color: #e6ffca;
            }
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                width: 100%;
                margin-left: 0;
                position: relative;
                z-index: 1;
            }

            .employee-content {
                margin-left: 0;
            }

            .employee-sidebar {
                transform: translateX(-100%);
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            }

            .employee-sidebar.active {
                transform: translateX(0);
            }

            .toggle-button {
                display: block;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>
    <button class="toggle-button" onclick="toggleSidebar()">☰</button>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <div class="employee-sidebar">
        <ul>
            @if (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'admin')
                <li><a href="{{ route('admin.dashboard') }}">
                        <img class="jj-logo" src="{{ asset('img/jjlogo.png') }}" alt="J&J Vegetables"> 
                    </a>
                </li>
                <li><a href="{{ route('admin.employees.index') }}">Manage Users</a></li>
                <li><a href="{{ route('admin.products.index') }}">Manage Products</a></li>
                <li><a href="{{ route('admin.orders.index') }}">View Orders</a></li>
                <li><a href="{{ route('admin.enquiries.index') }}">View Messages</a></li>
                <li><a href="{{ route('admin.reports.index') }}">Generate Report</a></li>
            @elseif (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'staff')
                <li><a href="{{ route('staff.dashboard') }}">
                        <img class="jj-logo" src="{{ asset('img/jjlogo.png') }}" alt="J&J Vegetables">
                    </a>
                </li> 
                <li><a href="{{ route('staff.products.index') }}">Manage Products</a></li>
                <li><a href="{{ route('staff.orders.index') }}">Manage Orders</a></li>
                <li><a href="{{ route('staff.enquiries.index') }}">Messages</a></li>
                <li><a href="{{ route('staff.reports.index') }}">Generate Report</a></li>
            @elseif (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'driver')
                <li><a href="{{ route('driver.dashboard') }}">
                        <img class="jj-logo" src="{{ asset('img/jjlogo.png') }}" alt="J&J Vegetables">
                    </a>
                </li>
                <li><a href="{{ route('driver.orders.index') }}">Manage Deliveries</a></li>
            @endif
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <div class="dashboard-container">
        @if (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'admin')
            <h1>Admin Dashboard</h1>
        @elseif (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'staff')
            <h1>Staff Dashboard</h1>
        @elseif (Auth::guard('employee')->check() && Auth::guard('employee')->user()->role === 'driver')
            <h1>Driver Dashboard</h1>
        @endif
    </div>

    <button id="go-to-top" title="Go to top">&#8679;</button>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.employee-sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (window.innerWidth > 768) {
                sidebar.classList.toggle('hide'); 
            } else {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }
            document.querySelector('.employee-content').classList.toggle('full-width');
            document.querySelector('.dashboard-container').classList.toggle('full-width');
        }

        document.querySelector('.sidebar-overlay').addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    </script>

    <script>
        //Get the button:
        let mybutton = document.getElementById("go-to-top");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        mybutton.onclick = function() {
            window.scroll({ 
                top: 0, 
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
