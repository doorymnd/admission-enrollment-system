@extends('layouts.adminLayout')

@section('content')

<link rel="stylesheet" href="{{ asset('css/semester.css') }}">

<div class="main-content">

    <div class="card settings-card">

        <div class="card-header">
            <h1 style="color:darkgreen">Academic Settings</h1>
            <p>Manage the active school year and semester using <strong>Super Admin</strong> account.</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- SCHOOL YEAR --}}
        <div class="form-group">
            <label>School Year</label>
            <input type="text" value="{{ $setting->school_year }}" readonly>
        </div>

        <form method="POST" action="{{ route('admin.new.schoolyear') }}">
            @csrf
            <div class="form-actions">
                <button type="submit" class="btn-cancel"
                    onclick="return confirm('Start a new school year? This will reset to 1st semester.')">
                    Start New School Year
                </button>
            </div>
        </form>

        <hr><br>

        {{-- SEMESTER + ENROLLMENT + GRADE TOGGLE FORM --}}
        <form method="POST" action="{{ route('admin.change.semester') }}">
            @csrf

            {{-- SEMESTER --}}
            <div class="form-group">
                <label>Semester</label>
                <select name="semester" required>
                    <option value="1" {{ $setting->semester == 1 ? 'selected' : '' }}>1st Semester</option>
                    <option value="2" {{ $setting->semester == 2 ? 'selected' : '' }}>2nd Semester</option>
                    <option value="3" {{ $setting->semester == 3 ? 'selected' : '' }}>Summer</option>
                </select>
            </div>

            {{-- ENROLLMENT --}}
            <div class="form-group">
                <label>Enrollment Status</label>
                <select name="enrollment_status" required>
                    <option value="open" {{ $setting->enrollment_status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ $setting->enrollment_status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            {{-- GRADE UPLOAD TOGGLE --}}
            <div class="form-group">
                <label>Faculty Grade Upload</label>

                <label class="switch">
                    <input type="checkbox" name="grade_upload_enabled" value="1"
                        {{ $setting->grade_upload_enabled ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>

                <small style="display:block; margin-top:5px;">
                    Turn ON to allow faculty to upload grades. Turn OFF to block submissions.
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    Update Academic Term
                </button>
            </div>

        </form>

    </div>

</div>

@endsection