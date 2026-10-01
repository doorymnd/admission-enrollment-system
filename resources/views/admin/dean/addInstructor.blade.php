@extends('layouts.deanLayout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/studentCSS/myProfile.css') }}">

<div class="main-content">

    <div class="profile-container">

        <h1>Faculty account creation</h1>
        <p>This form is for creating a faculty account.</p>

        <hr>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif


        <form method="POST" action="{{ route('dean.faculty.store') }}">
            @csrf

            <div class="form-row">

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text"
                           name="first_name"
                           placeholder="e.g Juan"
                           required>
                </div>

                <br>

                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text"
                           name="middle_name"
                           placeholder="e.g Santos">
                </div>

                <br>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text"
                           name="last_name"
                           placeholder="e.g Dela Cruz"
                           required>
                </div>

                <br>

                <div class="form-group">
                    <label>Suffix</label>
                    <input type="text"
                           name="suffix"
                           placeholder="e.g Jr., Sr., III">
                </div>

            </div>

            <br>

            <div class="form-row">

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email"
                           name="email"
                           placeholder="e.g Juan@example.com"
                           required>
                </div>

                <br>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="Instructor" {{ old('role', 'Instructor') == 'Instructor' ? 'selected' : '' }}>
                            Instructor
                        </option>
                    </select>
                </div>

            </div>

            <br>

<div class="form-group">
    <label>Department</label>

    <!-- hidden input (this is what gets submitted) -->
    <input type="hidden" name="department" value="{{ $admin->department }}">

    <!-- display فقط -->
    <input type="text" class="form-control" value="{{ $admin->department }}" readonly>
</div>

            <br>

            <button type="submit" class="btn-save">
                Create faculty
            </button>

        </form>
    </div>

</div>

@endsection