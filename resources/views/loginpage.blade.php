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
                <p>You are logged in.</p>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="btn btn-primary">Log out</button>
                </form>
            </div>
        @else
            <div class="auth-box">
                <h2>Login</h2>
                <form action="/login" method="POST">
                    @csrf
                    <div class="form-group">
                        <input name="loginname" type="text" class="form-control" placeholder="Name" value="{{ old('loginname') }}">
                        @error('loginname')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input name="loginpassword" type="password" class="form-control" placeholder="Password">
                        @error('loginpassword')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="btn btn-primary">Log in</button>
                </form>
                    <div style="font-size: 15px;">
                        <br>
                        <a href="#" style="color:goldenrod;">Forgot password?</a>
                    </div>
                    <div style="font-size: 15px;">
                        No account yet?
                        <a href="/registerpage" style="color:green;">Register now!</a>
                    </div>
            </div>
        @endauth
    </div>
@endsection
