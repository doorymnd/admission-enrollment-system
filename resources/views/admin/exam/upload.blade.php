@extends('layouts.adminLayout')

@section('content')
<div class="main-content">

    <div class="header-inline">
        <h1>Upload Exam Results</h1>
        <p>Upload Excel file containing passed students only.</p>
        <div class="p-button-wrapper">        
            <a href="{{ route('admin.students.index') }}" class="btn-view-approved">
                View Approved Students
            </a>
        </div>
    </div>


    <hr>


    @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="error-message">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <h4>Excel Format</h4>
    <p><strong style="color: red">Required format header:</strong> 
        You can download the template in Manage Admission tab.

        <table border="1" cellpadding="5" cellspacing="0" >
            <thead>
                <tr>
                    <th>email</th>
                    <th>primary_result</th>
                    <th>secondary_result</th>
                    <th>tertiary_result</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>gmail@example.com</td>
                    <td>failed</td>
                    <td>passed</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </p>
    <form id="importForm"
        action="{{ route('admin.admissions.import-passed') }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="drop-zone" id="dropZone">
            <input type="file" name="excel_file" id="excelInput" hidden required>

            <div class="drop-zone-content" id="dropContent">
                <p class="drop-text">Drag & Drop Excel File Here</p>
                <p class="drop-subtext">or click to browse (.xlsx, .xls)</p>
                <p id="file-name" class="file-name"></p>
            </div>
        </div>

        <br>

        <button type="submit" class="btn-view-approved">
            Upload Exam Result Excel
        </button>
    </form>


    <div id="progress-container" style="display:none; margin-top:20px;">
        <p><strong id="progress-title">Uploading...</strong></p>

        <div class="progress-wrapper">
            <div id="progress-bar" class="progress-bar">
                <span id="progress-percent">0%</span>
            </div>
        </div>

        <p id="progress-text" class="progress-text">
            0 / 0 processed
        </p>
    </div>

    <hr>


    <p style="color:red;">
        ⚠ Only <strong>PASSED</strong> students should be included in the Excel file. <br>
        ⚠ Input <strong>FAILED</strong> in failed exam result. Leave blank if already <strong>PASSED</strong>
        the exam.
    </p>
<script src="{{ asset('js/drag&drop.js') }}"></script>
</div>

@endsection
