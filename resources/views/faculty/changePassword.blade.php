@extends('layouts.facultyLayout') 

@section('content')

<style>

    .change-password-container h2 {
        margin-bottom: 25px;
        color: #1f6f3d;
        font-size: 28px;
        font-weight: 600;
    }

    .change-password-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-top: 5px solid #28a745;
    }

    .change-password-card label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #2f2f2f;
    }

    .change-password-card input {
        width: 97%;
        padding: 10px 12px;
        margin-bottom: 18px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: 0.3s;
    }

    .change-password-card input:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 6px rgba(40,167,69,0.25);
    }

    .change-password-btn {
        width: 100%;
        padding: 12px;
        background: #28a745;
        border: none;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    .change-password-btn:hover {
        background: #218838;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        border-left: 5px solid #28a745;
    }

    .error-text {
        color: #dc3545;
        font-size: 13px;
        margin-top: -12px;
        margin-bottom: 10px;
    }
</style>

<div class="main-content">
    <h2>Change Password</h2>
    <p>You can change faculty your Password here.</p>

    <hr>
    <br>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="change-password-card">
        <form method="POST" action="{{ route('faculty.change.pass.update') }}">
            @csrf

            {{-- Current Password --}}
            <label>Current Password</label>
            <input type="password" 
                   name="current_password" 
                   autocomplete="new-password"
                   autofocus
                   required >

            @error('current_password')
                <div class="error-text">{{ $message }}</div>
            @enderror

            {{-- New Password --}}
            <label>New Password</label>
            <input type="password" name="new_password" required>

            {{-- Confirm Password --}}
            <label>Confirm New Password</label>
            <input type="password" name="new_password_confirmation" required>

            <button type="submit" class="change-password-btn">
                Update Password
            </button>
        </form>
    </div>
</div>

@endsection