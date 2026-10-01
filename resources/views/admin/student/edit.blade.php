@extends('layouts.adminLayout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/adminCSS/StudentManage/editStudent.css') }}">
<div class="main-content">

    <header>
        <h1>Edit Student</h1>
        <p>Update student information</p>
    </header>
    
    <hr>

    {{-- Success / Error Messages --}}
    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="edit-form">
        @csrf
        @method('POST')

        {{-- PERSONAL INFORMATION --}}
        <h3 class="section-title">Personal Information</h3>

        <div class="form-grid">
            <div>
                <label>First Name</label>
                <input type="text" name="first_name"
                       value="{{ $student->info->first_name ?? '' }}" required>
            </div>

            <div>
                <label>Middle Name</label>
                <input type="text" name="middle_name"
                       value="{{ $student->info->middle_name ?? '' }}">
            </div>

            <div>
                <label>Last Name</label>
                <input type="text" name="last_name"
                       value="{{ $student->info->last_name ?? '' }}" required>
            </div>

            <div>
                <label>Phone</label>
                <input type="text" name="phone"
                       value="{{ $student->info->phone ?? '' }}">
            </div>

            <div>
                <label>Birthdate</label>
                <input type="date" name="birthdate"
                       value="{{ $student->info->birthdate ?? '' }}">
            </div>

            <div>
                <label>Gender</label>
                <select name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ ($student->info->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ ($student->info->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>

        <h3 class="section-title">Address</h3>
            <div class="form-grid">
            <div>
                <label>Street</label>
                <input type="text" name="street"
                    value="{{ $student->info->street ?? '' }}">
            </div>

            <div>
                <label>Barangay</label>
                <input type="text" name="barangay"
                    value="{{ $student->info->barangay ?? '' }}">
            </div>

            <div>
                <label>Municipality</label>
                <input type="text" name="municipality"
                    value="{{ $student->info->municipality ?? '' }}">
            </div>

            <div>
                <label>City</label>
                <input type="text" name="city"
                    value="{{ $student->info->city ?? '' }}">
            </div>
        </div>

        {{-- ENROLLMENT INFORMATION --}}
        <h3 class="section-title">Enrollment Information</h3>

        <div class="form-grid">
            <div>
                <label>Course</label>
                <input type="text" name="course"
                       value="{{ $student->enrollment->course ?? '' }}" required>
            </div>

            <div>
                <label>Year Level</label>
                <select name="year_level" required>
                    <option value="">Select Year</option>
                    <option value="1" {{ ($student->enrollment->year_level ?? '') == 1 ? 'selected' : '' }}>1st Year</option>
                    <option value="2" {{ ($student->enrollment->year_level ?? '') == 2 ? 'selected' : '' }}>2nd Year</option>
                    <option value="3" {{ ($student->enrollment->year_level ?? '') == 3 ? 'selected' : '' }}>3rd Year</option>
                    <option value="4" {{ ($student->enrollment->year_level ?? '') == 4 ? 'selected' : '' }}>4th Year</option>
                </select>
            </div>

            <div>
                <label>Section</label>
                <input type="text" name="section"
                       value="{{ $student->enrollment->section ?? '' }}" required>
            </div>

            <div>
                <label>Status</label>
                <select name="status">
                    <option value="regular" {{ ($student->enrollment->status ?? '') == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="irregular" {{ ($student->enrollment->status ?? '') == 'irregular' ? 'selected' : '' }}>Irregular</option>
                </select>
            </div>

            <div>
                <label>School Year</label>
                <input type="text" name="school_year"
                       value="{{ $student->enrollment->school_year ?? '' }}" required>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="form-actions">
            <button type="submit" class="btn-save">Update Student</button>
            <a href="{{ route('admin.students.index') }}" class="btn-cancel">Cancel</a>
        </div>

    </form>

</div>
@endsection
