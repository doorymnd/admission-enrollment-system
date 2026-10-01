@extends('layouts.facultyLayout')

@section('content')

<div class="main-content">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="header-inline">
        <h1>My Students</h1>
        <p>List of all the students handled by the faculty.</p>
    </div>

    <hr>

    {{-- FILTER --}}
    <form method="GET" style="margin-bottom:20px;">
        <label><strong>Filter Class:</strong></label>

        <select name="class_filter" onchange="this.form.submit()" style="padding:8px; min-width:250px;">
            <option value="">All Classes</option>

            @foreach($classOptions as $option)
                @php
                    [$sec, $sub] = explode('|', $option);
                @endphp

                <option value="{{ $option }}"
                    {{ request('class_filter') == $option ? 'selected' : '' }}>
                    {{ $sec }} | {{ $sub }}
                </option>
            @endforeach
        </select>

        <a href="{{ url()->current() }}"
           style="padding:8px 12px; background:#ccc; text-decoration:none; border-radius:5px; margin-left:10px;">
           Reset
        </a>
    </form>

    @if($data->count() > 0)

        @php
            // GROUP DATA
            $grouped = $data->groupBy(function ($ss) {
                return ($ss->student->enrollment->section ?? 'N/A')
                    . '|'
                    . ($ss->subject->code ?? 'N/A');
            });
        @endphp

        @foreach($grouped as $key => $students)

            @php
                [$section, $subjectCode] = explode('|', $key);
                $subjectName = $students->first()->subject->name ?? 'N/A';
            @endphp

            <div style="margin-bottom:30px; border:2px solid #ccc; border-radius:8px; padding:15px;">

                <h3 style="background:#bbefaa; padding:10px;">
                    Section: {{ $section }}
                </h3>

                <h4 style="background:#f5f5f5; padding:8px;">
                    Subject: {{ $subjectCode }} - {{ $subjectName }}
                </h4>

                <table border="1" cellpadding="10" width="100%">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Year Level</th>
                            <th>Semester</th>
                            <th>Units</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($students as $ss)
                            <tr>
                                <td>{{ $ss->student->student_no }}</td>
                                <td>
                                    {{ $ss->student->info->first_name ?? '' }},
                                    {{ $ss->student->info->last_name ?? '' }}
                                </td>
                                <td>{{ $ss->year_level ?? 'N/A' }}</td>
                                <td>{{ $ss->semester ?? 'N/A' }}</td>
                                <td>{{ $ss->subject->units ?? '0' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right"><strong>Download List :</strong></td>
                            <td style="text-align: center">
                                <a href="{{ route('faculty.downloadStudents', ['section' => $section, 'subject' => $subjectCode]) }}"
                                   style="padding:10px; background:green; color:white; text-decoration:none; border-radius:5px;">
                                   <i class="fa fa-download"></i> Excel
                                </a>
                            </td>
                        </tr>
                    </tfoot>

                </table>

            </div>

        @endforeach

    @else
        <p>No students found.</p>
    @endif

</div>

@endsection