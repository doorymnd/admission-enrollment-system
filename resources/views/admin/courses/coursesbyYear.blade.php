@extends('layouts.adminLayout')

@section('content')
<div class="main-content">

        <div class="header-inline">
            <h1>Subjects for {{ $course->code }} - {{ $course->name }}</h1>
        <div class="p-button-wrapper">
            <a href="{{ route('admin.subjects.index') }}" class="btn-view-approved">
                ← Back
            </a>
        </div>
        </div>

    @php
        // Year display names
        $yearNames = [
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year'
        ];

        // Force year to integer (prevents illegal offset)
        $yearKey = (int) $year;

        // Group subjects by semester
        $subjectsBySemester = $subjects->groupBy('semester');

        // Semester display names
        $semesterNames = [
            1 => '1st Semester',
            2 => '2nd Semester',
            3 => 'Summer'
        ];
    @endphp

    <h3>{{ $yearNames[$yearKey] ?? 'Unknown Year' }}</h3>
    <hr>

    @forelse ($subjectsBySemester as $semester => $semesterSubjects)

        <h4>{{ $semesterNames[$semester] ?? 'Unknown Semester' }}</h4>

        <table class="table-section" style="margin-bottom: 24px;">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Units</th>
                    <th>Pre-requisites</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($semesterSubjects as $subject)
                    <tr>
                        <td>{{ $subject->code }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->units }}</td>

                        <td>
                            @if($subject->prerequisites->count() > 0)
                                @foreach($subject->prerequisites as $prereq)
                                    {{ $prereq->code }}<br>
                                @endforeach
                            @else
                                None
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.subject.prerequisites.edit', $subject->id) }}"
                            style="background:#28a745; color:white; padding:5px 10px; border-radius:5px; text-decoration:none;">
                                Manage
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @empty
        <p>No subjects found for this year.</p>
    @endforelse

</div>
@endsection
