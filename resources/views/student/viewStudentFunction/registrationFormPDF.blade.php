@extends('layouts.studentLayout')

@section('content')

<style>
.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.table th, .table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

.table th {
    background: #f5f6fa;
}

.btn-download {
    background: #3490dc;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
}

.btn-download:hover {
    background: #2779bd;
}

.main-content {
    margin-left: 250px;
    padding: 40px;
}
</style>

<div class="main-content">

    <h2>Registration Forms</h2>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>School Year</th>
                <th>Semester</th>
                <th>Section</th>
                <th>Year Level</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($enrollments as $key => $enroll)
                <tr>
                    <td>{{ $key + 1 }}</td>

                    <td>{{ $enroll->school_year ?? '-' }}</td>

                    {{-- SEMESTER FIX --}}
                    <td>
                        @php
                            $semester = strtolower(trim($enroll->semester));

                            if (in_array($semester, ['1', '1st', 'first'])) {
                                $semesterLabel = '1st Semester';
                            } elseif (in_array($semester, ['2', '2nd', 'second'])) {
                                $semesterLabel = '2nd Semester';
                            } else {
                                $semesterLabel = $enroll->semester ?? '-';
                            }
                        @endphp

                        {{ $semesterLabel }}
                    </td>

                    <td>{{ $enroll->section ?? '-' }}</td>

                    {{-- YEAR LEVEL FIX --}}
                    <td>
                        @php
                            $year = (int) $enroll->year_level;

                            if ($year == 1) {
                                $yearLabel = 'First Year';
                            } elseif ($year == 2) {
                                $yearLabel = 'Second Year';
                            } elseif ($year == 3) {
                                $yearLabel = 'Third Year';
                            } elseif ($year == 4) {
                                $yearLabel = 'Fourth Year';
                            } else {
                                $yearLabel = $enroll->year_level ?? '-';
                            }
                        @endphp

                        {{ $yearLabel }}
                    </td>

                    <td>
                        <a href="{{ route('student.registration.pdf', $enroll->id) }}" 
                           target="_blank"
                           class="btn-download">
                           ⬇ Download
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No enrollment records found.</td>
                </tr>
            @endforelse
        </tbody>

    </table>

</div>

@endsection