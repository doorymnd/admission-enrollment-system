@extends('layouts.adminLayout')

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


            <form method="POST" action="{{ route('admin.faculty.store') }}">
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
                        <label>Last Name</label>
                        <input type="text"
                               name="last_name"
                               placeholder="e.g Dela Cruz">
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
                            <option value="Instructor" {{ old('role') == 'Instructor' ? 'selected' : '' }}>Instructor</option>
                            <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                </div>
                <br>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" id="department-select" required>
                            <option value="">-- Select Department --</option>
                            @foreach ($department as $dept)
                                <option value="{{ $dept->department }}">
                                    {{ $dept->department }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                <br>
                <button type="submit" class="btn-save">
                    Create faculty
                </button>

            </form>
        </div>

    </div>

@endsection