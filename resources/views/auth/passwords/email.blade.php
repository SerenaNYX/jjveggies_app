@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-box">
            <h2>Forgot Password</h2>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <button class="btn btn-primary">Send Reset Link</button>
            </form>

            <div style="font-size: 15px;">
                <br>
                <a href="/login" style="color:goldenrod;">Back to Login</a>
            </div>
        </div>
    </div>
@endsection