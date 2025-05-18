@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>
    <!-- Link to Manage Addresses -->
    <a href="{{ route('address.index') }}" class="btn" style="margin-top:-30px; margin-bottom:20px;">Manage Addresses</a>

    @if (session('verification-link-sent'))
        <div class="alert alert-success">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <!-- Email Verification Status Section -->
    <div class="verification-status">
        <div class="verification-status-content">
            <div class="verification-status-text">
                <span>Email Status:</span>
                @if ($user->hasVerifiedEmail())
                    <span class="verification-status-badge verified">Verified</span>
                @else
                    <span class="verification-status-badge unverified">Unverified</span>
                @endif
            </div>
            
            @unless ($user->hasVerifiedEmail())
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="verification-resend-btn">
                        Send Verification Email
                    </button>
                </form>
            @endunless
        </div>
        
        @unless ($user->hasVerifiedEmail())
            <p class="verification-help-text">
                Please check your email for a verification link. If you didn't receive it, click the button again.
            </p>
        @endunless
    </div>


    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-container">
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

            <!-- Email (display only) -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly disabled>
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
</div>
@endsection

<style>

    .profile-container {
        padding: 1rem 2rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Email Verification Status */
    .verification-status {
        background-color: #f1f8f1;
        border: 1px solid #63966b;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .verification-status-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .verification-status-text {
        display: flex;
        align-items: center;
        font-weight: 500;
        color: #2d4833;
    }

    .verification-status-icon {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
        color: currentColor;
    }

    .verification-status-badge {
        margin-left: 0.5rem;
    }

    .verification-status-badge.verified {
        color: #155724; /* Green for verified */
    }

    .verification-status-badge.unverified {
        color: #721c24; /* Red for unverified */
    }

    .verification-resend-btn {
        background-color: #63966b;
        color: white;
        border: 1px solid #44684a;
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .verification-resend-btn:hover {
        background-color: #44684a;
    }

    .verification-help-text {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #5f6c65;
    }
</style>