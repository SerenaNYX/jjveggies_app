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
                <h2>Register now</h2>
                <form action="/register" method="POST">
                    @csrf
                    <div class="form-group">
                        <input name="name" type="text" class="form-control" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input name="email" type="text" class="form-control" placeholder="Email" value="{{ old('email') }}">
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
                        <input name="contact" type="text" class="form-control" placeholder="Contact Number" value="{{ old('contact') }}">
                        @error('contact')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input name="address" type="text" class="form-control" placeholder="Address" value="{{ old('address') }}">
                        @error('address')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="btn btn-primary">Register</button>
                </form>
            </div>
        @endauth
    </div>
@endsection
