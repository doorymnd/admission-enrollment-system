@extends('layouts.studentLayout')

@section('content')

<div class="main-content">
<link rel="stylesheet" href="{{ asset('css/studentCSS/statusbadge.css') }}">
    <h2>My Enrollments</h2>

    {{-- ✅ CURRENT ENROLLMENT --}}
    @if($currentEnrollment)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                Current Enrollment
            </div>
            <div class="card-body">
                <p><strong>School Year:</strong> {{ $currentEnrollment->school_year }}</p>
                <p><strong>Course:</strong> {{ $currentEnrollment->course }}</p>
                <p><strong>Year Level:</strong> {{ $currentEnrollment->year_level }}</p>
                <p><strong>Semester:</strong> {{ $currentEnrollment->semester }}</p>
                <p><strong>Section:</strong> {{ $currentEnrollment->section }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge-success">
                        {{ ucfirst($currentEnrollment->status) }}
                    </span>
                </p>
            </div>
        </div>
    @endif

    {{-- ✅ ENROLLMENT TABLE --}}
    <div class="card">
        <div class="card-header">
            Enrollment History
        </div>

        <div class="card-body">
            @if($enrollments->isEmpty())
                <p>No enrollments found.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Semester</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Date Enrolled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enroll)
                            <tr>
                                <td>{{ $enroll->school_year }}</td>
                                <td>{{ $enroll->course }}</td>
                                <td style="text-align:center">{{ $enroll->year_level }}</td>
                                <td style="text-align:center">{{ $enroll->semester }}</td>
                                <td>{{ $enroll->section }}</td>
                               <td>
                                    @php
                                        $status = strtolower(trim($enroll->status));
                                    @endphp

                                    @if($status == 'pending')
                                        <span class="badge-warning">Pending</span>

                                    @elseif($status == 'approved')
                                        <span class="badge-primary">Approved</span>

                                    @elseif($status == 'regular')
                                        <span class="badge-success">Enrolled</span>

                                    @elseif($status == 'rejected')
                                        <span class="badge-danger">Rejected</span>

                                    @else
                                        <span class="badge-secondary">{{ $status }}</span>
                                    @endif
                                </td>
                                <td>{{ $enroll->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>

@endsection