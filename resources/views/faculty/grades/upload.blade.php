@extends('layouts.facultyLayout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/facultyCSS/uploadGrades.css') }}">
<div class="main-content">

    <div class="header-inline">
        <h1>Upload Grades</h1>
        <p>Upload Excel file contains students students number and grades.</p>

        <div class="p-button-wrapper">
            <a href="{{ route('view.uploaded.grades') }}" class="btn-view-approved">
                View Uploaded Grades
            </a>
        </div>
    </div>
    <hr>


    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="color: red;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <h4>Excel Format</h4>
    <p>The Excel file must contain this column:</p>
    <p><strong style="color: red">Required format header:</strong> 
        <table border="1" cellpadding="5" cellspacing="0" >
            <thead>
                <tr>
                    <th>Student No</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>A26-0000</td>
                    <td>97.12</td>
                </tr>
            </tbody>
        </table>
    </p>
    <hr>

    @php
        $setting = \App\Models\Admin\SchoolSetting::first();
    @endphp

    <form id="uploadGrades"
        action="{{ route('faculty.grades.upload') }}"
        method="POST"
        enctype="multipart/form-data"
        {{ !$setting || !$setting->grade_upload_enabled ? 'style=pointer-events:none;opacity:0.5;' : '' }}>

        @csrf

            @php
                $semesterNames = [
                    1 => 'First Semester',
                    2 => 'Second Semester',
                    3 => 'Summer'
                ];


                $school = \App\Models\Admin\SchoolSetting::first();
                $activeSemester = $school ? $school->semester : 1;
            @endphp

            <div class="form-row">
<div class="form-group">
    <label>Select Section & Subject</label>

    <select name="assignment_id" required>
    <option value="">-- Select --</option>

    @foreach($assignments as $assign)

        @php
            $grade = \App\Models\Admin\Grade::where('assignment_id', $assign->id)
                ->where('faculty_id', auth()->id())
                ->first();

            $status = $grade ? $grade->status : null;
        @endphp

        <option value="{{ $assign->id }}"
            {{ in_array($status, ['submitted','approved']) ? 'disabled' : '' }}>

            {{ $assign->section }}
            - {{ $semesterNames[$assign->semester] ?? 'Unknown' }}
            - {{ $assign->subject->name }}

            @if($status == 'submitted')
                (Submitted)
            @elseif($status == 'approved')
                (Approved)
            @elseif($status == 'pending')
                <strong>(Draft)</strong>
            @elseif($status == 'edit_request')
                (Edit Allowed)
            @endif

        </option>

    @endforeach
</select>
</div>

            <div class="form-group">
                <label>School Year</label>
                <input type="text" name="school_year" value="{{ $currentSchoolYear }}" readonly>
            </div>
        </div>

        <div for="excelInput" class="drop-zone" id="dropZone">
            <input type="file" name="file" id="excelInput" accept=".xlsx, .xls" hidden required>

            <div class="drop-zone-content" id="dropContent">
                <p class="drop-text">Drag & Drop Excel File Here</p>
                <p class="drop-subtext">or click to browse (.xlsx, .xls)</p>
                <p id="file-name" class="file-name"></p>
            </div>
        </div>

        <br>

        <button type="submit" class="btn-view-approved">Upload Grades</button>
    </form>
</div>
    <script src="{{ asset('js/facultyUploadGrades.js') }}"></script>
@endsection