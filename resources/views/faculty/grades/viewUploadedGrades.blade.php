@extends('layouts.facultyLayout')

@section('content')
<div class="main-content">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="header-inline">
        <h1>All Uploaded Grades</h1>
        <p>View list of uploaded grades and monitor their status</p>

        <div class="p-button-wrapper">
            <a href="{{ route('showForm.grades.upload') }}" class="btn-view-approved">
                Upload Grades
            </a>
        </div>
    </div>

    <hr>

    @foreach($grades as $subjectId => $subjectGrades)

        @php
            $first = $subjectGrades->first();
            $isEditable = in_array($first->status, ['pending', 'edit_request']);
        @endphp

        <div style="margin-bottom:30px; border:2px solid #ccc; border-radius:8px; padding:15px;">

            <h2 style="text-align: center">
                {{ $first->section }}
            </h2>

            <h3 style="background:#bbefaa; padding:10px;">
                {{ $first->subject->code }} - {{ $first->subject->name }}
            </h3>

            {{-- ✅ SUBMIT GRADES --}}
            @if (in_array($first->status, ['pending', 'edit_request'])) 

    <form id="submitGradesForm-{{ $first->assignment_id }}" 
          method="POST" 
          action="{{ route('faculty.grades.submit') }}">
        @csrf

        <input type="hidden" name="assignment_id" value="{{ $first->assignment_id }}">

        <button type="button"
                onclick="confirmSubmit('{{ $first->assignment_id }}')"
                style="background:rgb(21, 110, 21);color:white;padding:10px;margin-bottom:10px; cursor: pointer;">

            @if(in_array($first->status, ['pending', 'edit_request']))
                Submit Grades
            @else
                Resubmit Grades
            @endif

        </button>

    </form>

@endif

            {{-- ✅ REQUEST EDIT --}}
            @if(in_array($first->status, ['submitted', 'approved']))
                <form method="POST"
                      action="{{ route('faculty.grades.requestEditSubject', $first->assignment_id) }}">
                    @csrf
                    <button style="background:orange;color:white;padding:10px;margin-bottom:10px;">
                        Request Edit (Subject)
                    </button>
                </form>
            @endif

            {{-- 🔴 RETURNED BY DEAN --}}
            @if($first->status === 'edit_request')
                <div style="background:#ffe6e6; padding:10px; margin-bottom:10px; border-left:5px solid red;">

                    <strong style="color:red;">
                        ⛔ Returned by Dean for Revision
                    </strong>

                    @if($first->dean_note)
                        <div style="margin-top:5px;">
                            <strong>Reason:</strong>
                            <span style="color:darkred;">
                                {{ $first->dean_note }}
                            </span>
                        </div>
                    @endif

                    <br>

                    <span style="color:orange;">
                        ⏳ Please update and resubmit grades
                    </span>

                </div>
            @endif

            {{-- 🔒 RELEASED --}}
            @if($first->status === 'released')
                <span style="color:gray; display:block; margin-bottom:10px;">
                    🔒 Grades Released (Locked)
                </span>
            @endif

            {{-- 🔥 UPDATE FORM --}}
            <form method="POST" action="{{ route('faculty.grades.update') }}">
                @csrf

                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th>Student Number</th>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Raw Grade</th>
                            @if($isEditable)
                                <th>Save</th>
                            @endif
                            <th>Final Grade</th>
                            <th>Remarks</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($subjectGrades as $grade)
                            <tr>

                                <td>{{ $grade->student->student_no }}</td>

                                <td>
                                    {{ $grade->student->info->first_name }}
                                    {{ $grade->student->info->last_name }}
                                </td>

                                <td>{{ $grade->subject->units ?? 'N/A' }}</td>

                                {{-- RAW GRADE --}}
                                <td>
                                    @if($grade->status === 'pending')
                                        <input type="number"
                                               step="0.01"
                                               name="grades[{{ $grade->id }}][raw_grade]"
                                               value="{{ $grade->raw_grade }}">
                                    @else
                                        {{ $grade->raw_grade }}
                                    @endif
                                </td>

                                {{-- SAVE BUTTON --}}
                                @if($isEditable)
                                    <td>
                                        <button type="submit"
                                                style="background:green;color:white;border:none;padding:5px;">
                                            Save
                                        </button>
                                    </td>
                                @endif

                                <td>{{ $grade->final_grade }}</td>

                                <td>
                                    @if($grade->final_grade <= 3)
                                        Passed
                                    @elseif($grade->final_grade == 4)
                                        Incomplete
                                    @elseif($grade->final_grade == 5)
                                        Failed
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($grade->status === 'pending')
                                        Editable

                                    @elseif($grade->status === 'edit_request')
                                        Waiting Revision

                                    @elseif($grade->status === 'submitted')
                                        Pending

                                    @elseif($grade->status === 'approved')
                                        Approved

                                    @elseif($grade->status === 'released')
                                        Released
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </form>

        </div>

    @endforeach

</div>

<script src="{{ asset('js/adminGradesPopUp.js') }}"></script>

@endsection