@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-box">
            <h2>Reset Password</h2>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <input name="email" type="email" class="form-control" placeholder="Email" value="{{ $email }}" required>
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control" placeholder="New Password" required>
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm New Password" required>
                </div>
                <button class="btn btn-primary">Reset Password</button>
            </form>
        </div>
    </div>
@endsection