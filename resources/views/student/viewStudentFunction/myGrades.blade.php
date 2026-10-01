@extends('layouts.studentLayout')

@section('content')

<div class="main-content">
<style>
        .left {
            text-align: left;
        }

        .right {
            text-align: right
        }

        .center{
            text-align: center
        }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @php 
        $semesterNames = [ 
            1 => 'First Semester', 
            2 => 'Second Semester', 
            3 => 'Midyear' 
        ]; 
    @endphp

    @php
        $totalUnits = 0;
        $totalGrades = 0;
        $gradedCount = 0;
    @endphp


    <div class="header-inline">
        <h1>
            Grades for {{ $school_year }} - {{ $semesterNames[$semester] ?? 'Semester' }}
        </h1>
    </div>
    <hr><br>

    <div style="margin-bottom:30px; border:2px solid #ccc; border-radius:8px; padding:15px;">

        <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Units</th>
                <th>Grade</th>
                <th>Final Grade</th>
                <th>Remarks</th>
                <th>Instructor</th>
            </tr>
        </thead>

        <tbody>
            @forelse($subjects as $sub)
                @php
                    // match grade using subject_id
                    $grade = $grades[$sub->subject_id] ?? null;


                    $units = $sub->subject->units ?? 0;
                    $totalUnits += $units;

                    if($grade && $grade->final_grade !== null) {
                        $totalGrades += $grade->final_grade;
                        $gradedCount++;
                    }
                @endphp

                <tr>
                    {{-- SUBJECT INFO --}}
                    <td class="center">{{ $sub->subject->code ?? 'N/A' }}</td>
                    <td class="left">{{ $sub->subject->name ?? 'N/A' }}</td>
                    <td class="center">{{ $sub->subject->units ?? 0 }}</td>

                    {{-- GRADE --}}
                    <td class="center">
                        {{ ($grade && $grade->status === 'released') ? $grade->raw_grade : '-' }}
                    </td>

                    <td class="center">
                        {{ ($grade && $grade->status === 'released') ? $grade->final_grade : '-' }}
                    </td>



                    

                    

                    {{-- REMARKS --}}
                    <td class="center">
                        @if($grade && $grade->status === 'released')

                        @if($grade->final_grade <= 3.00)
                            Passed

                        @elseif($grade->final_grade == 4.00)
                            Incomplete

                        @elseif($grade->final_grade == 5.00)
                            Failed

                        @else
                            Invalid Grade
                        @endif

                    @else
                        -
                    @endif
                    </td>

                    {{-- INSTRUCTOR --}}
                    <td>
                        @if($grade && $grade->faculty)
                            {{ $grade->faculty->first_name }}, {{ $grade->faculty->last_name }}
                        @else
                            TBA
                        @endif
                    </td>


                    
                </tr>

            @empty
                <tr>
                    <td colspan="6">No enrolled subjects found.</td>
                </tr>
            @endforelse
        </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>

                    {{-- TOTAL UNITS --}}
                    <td class="center">
                        <strong>{{ $totalUnits }}</strong>
                    </td>

                    {{-- GWA LABEL --}}
                    <td class="right"><strong>GWA:</strong></td>

                    {{-- AVERAGE GRADE --}}
                    <td class="center">
                        <strong>
                            @if($gradedCount > 0)
                                {{ number_format($totalGrades / $gradedCount, 2) }}
                            @else
                                -
                            @endif
                        </strong>
                    </td>

                    <td></td>
                    <td>
                        <a href="{{ route('student.grades.download', [$school_year, $semester]) }}"
                        class="btn btn-success"
                        style="padding:10px; background:green; color:white; text-decoration:none; border-radius:5px;">
                            <i class="fa fa-download" aria-hidden="true"></i> PDF
                        </a>
                    </td>
                </tr>
            </tfoot>
    </table>

    </div>

</div>

@endsection