@extends('layouts.employee')

@section('content')
<div class="container">
    <h1>Staff Dashboard</h1>
    <p>Welcome, {{ Auth::guard('employee')->user()->name }}!</p>
    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection
