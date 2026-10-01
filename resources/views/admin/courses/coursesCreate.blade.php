@extends('layouts.adminLayout')

@section('content')
<div class="main-content">

    <div class="header-inline">
        <h1>Add New Course</h1>
        <p>Create a course under a program.</p>
        <div class="p-button-wrapper">
            <a href="{{ route('admin.courses.coursesPanel') }}" class="btn-view-approved">
                View All Courses
            </a>
        </div>
    </div>

    <hr>

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('admin.courses.store') }}" method="POST">
            @csrf

            <h3>Program Information</h3>
            <div class="form-group">
                <label>Department</label>
                <select name="department" class="form-control" required>
                    <option value="" disabled selected>Select Department</option>
                    <option value="Criminology">Criminology</option>
                    <option value="Education">Education</option>
                    <option value="Governance">Governance</option>
                    <option value="Management">Management</option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <label>Program Code</label>
                <input type="text" name="code" class="form-control" placeholder="e.g BPA" required>
            </div>
                <br>
            <div class="form-group">
                <label>Program Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g Bachelor of Public Administration" required>
            </div>
                <br>
            <div class="form-group">
                <label>Duration (Years)</label>
                <input type="number" name="duration_years" class="form-control" min="1" value="4" required>
            </div>
            <br>
            <div class="form-group">
                <label>Section Code</label>
                <input type="yext" name="section_code" class="form-control" placeholder="e.g PA" required>
            </div>

                <br>
            <button type="submit" class="btn-save">Save Course</button>
        </form>

    </div>
</div>
@endsection
