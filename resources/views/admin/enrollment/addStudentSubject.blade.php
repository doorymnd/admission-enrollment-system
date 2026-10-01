@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <h2>Add Subjects</h2>
    <hr>

    {{-- SUCCESS / ERROR --}}
    @if(session('success'))
        <div style="padding:10px; background:#d4edda; color:#155724; border-radius:5px; margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:10px; background:#f8d7da; color:#721c24; border-radius:5px; margin-bottom:10px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- STUDENT INFO --}}
<div style="background:#fff; padding:15px; border-radius:8px; margin-bottom:15px; display:grid; grid-template-columns: 1fr 1fr; gap:10px;">

    <div>
        <strong>Student:</strong>
        {{ $studentInfo->first_name ?? '' }} {{ $studentInfo->last_name ?? '' }}
    </div>

    <div>
        <strong>Year:</strong>
        {{ $yearLabel }}
    </div>

    <div>
        <strong>Student ID:</strong>
        {{ $student->student_no ?? 'N/A' }}
    </div>

    <div>
        <strong>Semester:</strong>
        {{ $semesterLabel }}
    </div>

    <div>
        <strong>Course:</strong>
        {{ $latestEnrollment->course_name ?? 'N/A' }}
    </div>

</div>

    {{-- SEARCH --}}
    <form method="GET" style="display:flex; gap:10px; margin-bottom:15px;">
        <input type="text" name="search"
               placeholder="Search subject..."
               value="{{ $search ?? '' }}"
               style="padding:8px; width:250px;">

        <button type="submit" class="btn btn-save">Search</button>
    </form>


    {{-- ========================= --}}
    {{-- ADDED SUBJECTS TABLE --}}
    {{-- ========================= --}}
    <div style="background:#fff; padding:20px; border-radius:8px; margin-bottom:20px;">

        <h3>Added Subjects</h3>

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th>Code</th>
                    <th>Subject</th>
                    <th>Units</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($enrolledSubjects as $subject)
                    <tr>
                        <td>{{ $subject->code }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->units }}</td>

                        <td style="color:green; font-weight:bold; text-align:center;">
                            ✔ Added
                        </td>

                        <td style="text-align:center;">
                            <form method="POST"
                                  action="{{ url('/admin/student/'.$student->id.'/remove-subject') }}">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                                <button style="background:red; color:white; padding:5px 8px; border:none;">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            No subjects added yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            
            <tfoot>
                <tr>
                    <td></td>
                    <td style="text-align: right"><strong>Total units:</strong></td>
                    <td><strong>{{ $totalUnits}}</strong></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>

        </table>
    </div>


   {{-- ========================= --}}
{{-- AVAILABLE SUBJECTS --}}
{{-- ========================= --}}
<div style="background:#fff; padding:20px; border-radius:8px;">

    <h3>Available Subjects</h3>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th>Code</th>
                <th>Subject</th>
                <th>Year</th>
                <th>Semester</th>
                <th>Units</th>
                <th>Course</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($subjects as $subject)

                @php
                    $prereqList = $prerequisites[$subject->id] ?? collect();
                    $prereqIds = $prereqList->pluck('prerequisite_id')->toArray();

                    $missingPrereq = false;

                    if (!empty($prereqIds)) {
                        foreach ($prereqIds as $prereqId) {
                            if (!in_array($prereqId, $passedSubjectIds)) {
                                $missingPrereq = true;
                                break;
                            }
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $subject->code }}</td>
                    <td>{{ $subject->name }}</td>
                    <td>{{ $subject->year_level }}</td>
                    <td>{{ $subject->semester }}</td>
                    <td>{{ $subject->units }}</td>
                    <td>{{ $subject->course_name ?? 'N/A' }}</td>

                    <td style="text-align:center;">

                        {{-- Already added --}}
                        @if(in_array($subject->id, $addedSubjectIds))

                            <button disabled style="background:gray; color:white;">
                                Already Added
                            </button>

                        {{-- Missing prerequisite --}}
                        @elseif($missingPrereq)

                            <button disabled style="background:orange; color:white;">
                                Prerequisite Needed
                            </button>

                        {{-- Add button --}}
                        @else

                            <form method="POST"
                                  action="{{ url('/admin/student/'.$student->id.'/add-subjects') }}">

                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                                <button type="submit" class="btn btn-save">
                                    Add
                                </button>

                            </form>

                        @endif

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        No subjects found
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>
</div>

@endsection