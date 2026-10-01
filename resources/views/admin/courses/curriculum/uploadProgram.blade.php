@extends('layouts.adminLayout')

@section('content')
<div class="main-content">

    <header>
        <h1>Upload Curriculum</h1>
        <p>Import subjects via Excel</p>
    </header>
    <hr>

    @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif

    <h4>Excel Format</h4>
    <p><strong style="color: red">Required format header:</strong> 
        You can download the template in Manage Admission tab.

        <table border="1" cellpadding="5" cellspacing="0" >
            <thead>
                <tr>
                    <th>course_code</th>
                    <th>course_name</th>
                    <th>year_level</th>
                    <th>semester</th>
                    <th>subject_code</th>
                    <th>subject_name</th>
                    <th>units</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center">BSCA</td>
                    <td>Bachelor of Science in Customs Administration</td>
                    <td style="text-align: center">1</td>
                    <td style="text-align: center">1</td>
                    <td style="text-align: center">CUSTM101</td>
                    <td>Border Control and Security</td>
                    <td style="text-align: center">3</td>
                </tr>
            </tbody>
        </table>
    </p>
    <hr>

    <form method="POST"
        action="{{ route('admin.curriculum.store') }}"
        enctype="multipart/form-data">

        @csrf

        <label for="program_id">Program : </label>
        <select name="program_id" id="program_id" required>
            <option value="">-- Select Program --</option>
            @foreach($programs as $program)
                <option value="{{ $program->id }}">
                    {{ $program->code }} - {{ $program->name }}
                </option>
            @endforeach
        </select>

        <label for="excel_file">Excel File : </label>
        <input type="file" name="excel_file" id="excel_file" required>
<hr>
        <button type="submit" class="btn-upload">
            Upload Curriculum
        </button>
    </form>

</div>
@endsection
