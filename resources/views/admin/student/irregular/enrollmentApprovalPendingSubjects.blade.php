@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <h2>Irregular Enrollment Requests</h2>

    {{-- ALERTS --}}
    @if(session('success'))
        <div style="color:green; margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color:red; margin-bottom:10px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- NO DATA --}}
    @if($requests->isEmpty())
        <p>No irregular enrollment requests found.</p>
    @endif

    {{-- GROUP BY STUDENT --}}
    @foreach($requests->groupBy('student_id') as $studentId => $studentRequests)

        @php
            $first = $studentRequests->first();
        @endphp

        {{-- STUDENT CARD --}}
        <div style="
            background:#f8f9fa;
            padding:15px;
            margin-top:25px;
            border-left:5px solid green;
            border-radius:5px;
        ">

            <h3 style="margin:0;">
                {{ $first->first_name }} {{ $first->last_name }}
            </h3>

            <small style="color:gray;">
                Student ID: {{ $first->student_no }}
            </small>

            <br><br>

            {{-- SUBJECT TABLE --}}
            <table border="1" width="100%" cellpadding="10" style="border-collapse:collapse;">

                <thead style="background:#eee;">
                    <tr>
                        <th>Subject</th>
                        <th>Year Level</th>
                        <th>Semester</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($studentRequests as $req)

                    <tr>
                        <td>{{ $req->subject_name }}</td>
                        <td>{{ $req->year_level }}</td>
                        <td>{{ $req->semester }}</td>

                        <td>
                            <span style="color:orange; font-weight:bold;">
                                Pending
                            </span>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>

            <br>

            {{-- APPROVE ALL --}}
            <form action="{{ route('admin.irregular.approveAll', $studentId) }}"
                  method="POST"
                  onsubmit="return confirm('Approve ALL subjects for this student?')">

                @csrf

                <button style="
                    background:#007bff;
                    color:white;
                    border:none;
                    padding:8px 15px;
                    border-radius:5px;
                ">
                    Approve All Subjects
                </button>

            </form>

        </div>

    @endforeach

</div>

@endsection