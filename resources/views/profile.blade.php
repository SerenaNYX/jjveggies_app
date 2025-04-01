<!-- resources/views/profile.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>
    <!-- Link to Manage Addresses -->
    <a href="{{ route('address.index') }}" class="button" style="margin-top:-30px; margin-bottom:20px;">Manage Addresses</a>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Update Form -->
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Contact -->
        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="tel" name="contact" id="contact" class="form-control" value="{{ old('contact', $user->contact) }}" maxlength="12" required oninput="validateContact(this)">
            <script>
                function validateContact(input) {
                    input.value = input.value.replace(/[^0-9]/g, '');
                }
            </script>
            @error('contact')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password (leave blank to keep current password)</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection