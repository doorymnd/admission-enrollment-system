@extends('layouts.adminLayout')

@section('content')
<div class="main-content">
    <h2>Edit Admin Account</h2>
    <div class="admin-register-container">
        

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('update.admin.acc', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name"
                    value="{{ old('first_name', $admin->first_name) }}" required>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name"
                    value="{{ old('last_name', $admin->last_name) }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email"
                    value="{{ old('email', $admin->email) }}" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" {{ $admin->status === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="disabled" {{ $admin->status === 'disabled' ? 'selected' : '' }}>
                        Disabled
                    </option>
                </select>
            </div>
            <br>

            <div class="form-actions">
                <button type="submit" class="btn-save">Update Admin</button>
                <a href="{{ route('admin.accounts') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
