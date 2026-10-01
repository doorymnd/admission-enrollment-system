@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <div class="header-inline">
        <h1>Import Existing Students</h1>
        <p>Upload Excel file contains old students informations.</p>
    </div>
    <hr>    

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
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
    <p>The Excel file must contain this column:</p>
    <p><strong style="color: red">Required format header:</strong> 
        course | student_no | email | first_name | middle_name | last_name |<br> suffix | phone | street | 
        barangay | municipality | city | birth_date | sex | school_year | year_level | semester | section | status
    </p>
    <p><strong style="color: red">Download Header format file:</strong>
        <a href="{{ route('admin.download.old_student_header') }}" class="btn-view-approved">PDF</a>
    </p>



    <form id="importOldForm"
          data-url="{{ route('admin.students.importOld') }}"
          enctype="multipart/form-data">

          @csrf

            <div for="excelInput" class="drop-zone" id="dropZone">
                <input type="file" name="file" id="excelInput" accept=".xlsx, .xls" hidden required>

                <div class="drop-zone-content" id="dropContent">
                    <p class="drop-text">
                        Drag & Drop Excel File Here
                    </p>
                    <p class="drop-subtext">
                        or click to browse (.xlsx, .xls)
                    </p>
                    <p id="file-name" class="file-name"></p>
                </div>
            </div>

        <br>
        <button type="submit" class="btn-view-approved">
            Import Old Students
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

</div>
    <script src="{{ asset('js/dropfile.js') }}"></script>

@endsection





