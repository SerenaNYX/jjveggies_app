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
                    <h2>Login</h2>
                    <p>Access your account to continue</p>
                </div>
                
                <form action="/login" method="POST" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <input name="loginemail" type="text" class="form-control" placeholder="Email Address" value="{{ old('loginemail') }}">
                        @error('loginemail')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input name="loginpassword" type="password" class="form-control" placeholder="Password">
                        @error('loginpassword')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-options">
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                    </div>
                    
                    <button class="auth-btn btn">Log in</button>
                    
                    <div class="auth-footer">
                        <p>No account yet? <a href="/registerpage" class="auth-link">Register now!</a></p>
                    </div>
                </form>
            </div>
        @endauth
    </div>
@endsection