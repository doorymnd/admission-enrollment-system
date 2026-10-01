@extends('layouts.facultyLayout')
<style>
    .alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 5px solid #28a745;
    padding: 10px;
    border-radius: 5px;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 5px solid #dc3545;
    padding: 10px;
    border-radius: 5px;
}
</style>
@section('content')

    <div class="main-content">
        <header>
            <h1>Faculty Dashboard</h1>
            <p>
                Welcome <strong>{{ (auth()->user()->first_name) }} {{ (auth()->user()->last_name) }}</strong>
            </p>
            <hr>
        </header>

        <section class="cards">
            <a href="{{ route('faculty.mySubjects') }}" class="card">
                <h3 style="text-align: center">My Subjects</h3>
            </a>
            <a href="{{ route('showForm.grades.upload') }}" class="card">
                <h3 style="text-align: center">Upload Grades</h3>
            </a>
            <a href="{{ route('view.uploaded.grades') }}" class="card">
                <h3 style="text-align: center">View Grades</h3>
            </a>
        </section>

        @php
    $setting = \App\Models\Admin\SchoolSetting::first();
@endphp

@if($setting && $setting->grade_upload_enabled)
    <div class="alert-success" style="padding:10px; margin-bottom:10px;">
        🟢 You are now allowed to upload grades.
    </div>
@endif

    </div>

@endsection