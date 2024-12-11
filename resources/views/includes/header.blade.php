<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>J&J Vegetables</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>
    <header>
        <div class="account">
            @guest
                <div class="login">
                    <a href="/loginpage">Login</a>
                </div>
                <div class="signup">
                    <a href="/registerpage">Sign Up</a>
                </div>
            @else
                <div class="profile">
                    <a href="/profile">My Profile</a>
                </div>
                <div class="logout">
                    <form action="/logout" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                </div>
            @endguest
        </div>

        <div class="top-nav container">
            <div class="logo">
                <a href="/">
                    <img src="{{ asset('img/logo.jpg') }}" alt="J&J Vegetables"> J&J Vegetables
                </a>
            </div>
            <ul>
                @guest
                    <li><a href="/product">Shop</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                @else
                    <li><a href="/product">Shop</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#"><img src={{ asset('img/cart.png') }} alt=""></a></li>
                @endguest
            </ul>
        </div> <!-- end top-nav -->
        
        {{-- Conditionally include the hero section --}}
        @yield('hero')

    </header>
</body>
</html>
