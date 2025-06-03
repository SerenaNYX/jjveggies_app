@extends('layouts.app')

@section('content')
    <div class="auth-container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @auth
            <div class="auth-box">
                <div class="auth-header">
                    <img class="jj-logo" src="{{ asset('img/jjlogo.png') }}" alt="J&J Vegetables" 
                        style=" width: 30%; height: auto; margin-top: 2rem; border: 3px solid #63966b; border-radius: 50%;"> 
                    <h2>Welcome Back</h2>
                    <p>You are already logged in.</p>
                </div>
                <form action="/logout" method="POST" class="auth-form">
                    @csrf
                    <button class="btn auth-btn">Log out</button>
                </form>
            </div>
        @else
            <div class="auth-box">
                <div class="auth-header">
                    <img class="jj-logo" src="{{ asset('img/jjlogo.png') }}" alt="J&J Vegetables" 
                        style=" width: 30%; height: auto; margin-top: 2rem; border: 3px solid #63966b; border-radius: 50%;"> 
                    <h2>Create Account</h2>
                    <p>Join us today</p>
                </div>
                
                <form action="/register" method="POST" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <input name="name" type="text" class="form-control" placeholder="Username" value="{{ old('name') }}">
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input name="email" type="text" class="form-control" placeholder="Email Address" value="{{ old('email') }}">
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input name="password" type="password" class="form-control" placeholder="Password">
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
                    </div>
                    <div class="form-group">
                        <input name="contact" type="tel" class="form-control" placeholder="Phone Number" value="{{ old('contact')}}" maxlength="12" required oninput="validateContact(this)">
                        @error('contact')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button class="auth-btn btn">Create Account</button>
                    
                    <div class="auth-footer">
                        <p>Already have an account? <a href="/login" class="auth-link">Sign in</a></p>
                    </div>
                </form>
            </div>
        @endauth
    </div>
    
    <script>
        function validateContact(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
@endsection