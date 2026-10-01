@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <h2>Student List (Per Section Promotion)</h2>

    {{-- SUCCESS / ERROR --}}
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    <br>

    @foreach($students as $sectionName => $sectionStudents)

        @php
            $firstStudent = $sectionStudents->first();
        @endphp

        {{-- SECTION HEADER --}}
        <div style="display:flex; justify-content:space-between; align-items:center; background:#f0f0f0; padding:10px; margin-top:15px;">

            <h3 style="margin:0;">
                Section: {{ $sectionName }}
            </h3>

            {{-- AUTO PROMOTION BUTTON --}}
            @if($firstStudent->semester == 1)
                <form action="{{ route('admin.promote.section.semester', $sectionName) }}"
                      method="POST"
                      onsubmit="return confirm('Promote ALL students to 2nd semester?')">
                    @csrf
                    <button style="background:blue; color:white; padding:8px 12px;">
                        Promote Section (2nd Sem)
                    </button>
                </form>
            @else
                <form action="{{ route('admin.promote.section', $sectionName) }}"
                      method="POST"
                      onsubmit="return confirm('Promote ALL students to next year?')">
                    @csrf
                    <button style="background:green; color:white; padding:8px 12px;">
                        Promote Section (Year Level)
                    </button>
                </form>
            @endif

        </div>

        {{-- MAIN STUDENT TABLE --}}
        <table border="1" cellpadding="10" width="100%" style="margin-bottom:30px;">
            <tr>
                <th>Name</th>
                <th>Year Level</th>
                <th>Semester</th>
                <th>Section</th>
                <th>Action</th>
            </tr>

            @foreach($sectionStudents as $student)

                <tr>
                    <td>
                        {{ $student->student->info->first_name ?? '' }},
                        {{ $student->student->info->last_name ?? '' }}
                    </td>

                    <td>{{ $student->year_level }}</td>
                    <td>{{ $student->semester }}</td>
                    <td>{{ $student->section }}</td>

<td>

    {{-- ========================= --}}
    {{-- 1ST SEMESTER --}}
    {{-- ========================= --}}
    @if ($student->semester == 1)

        <form action="{{ route('admin.promote.semester', $student->id) }}" method="POST">
            @csrf
            <button class="btn btn-save">Promote 2nd Sem</button>
        </form>

    {{-- ========================= --}}
    {{-- 2ND SEMESTER --}}
    {{-- ========================= --}}
    @elseif ($student->semester == 2)

        {{-- HAS MANDATORY SUMMER --}}
        @if ($student->has_mandatory_summer)

            @if ($student->status == 'pending')

                <form action="{{ route('admin.promote.summer.approve', $student->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-save">Approve Mid Year (Required Summer)</button>
                </form>

            @elseif ($student->status == 'completed')

                <span>Completed Mid Year</span>

            @else

                <button disabled style="color:red;">
                    Must complete Summer first
                </button>

            @endif

        {{-- NO SUMMER REQUIRED --}}
        @else

            @if ($student->status == 'pending')

                <form action="{{ route('admin.promote.year', $student->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-save">Promote Year</button>
                </form>

            @elseif ($student->status == 'completed')

                <span>Completed Mid Year</span>

            @else

                <form action="{{ route('admin.promote.year', $student->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-save">Promote Year</button>
                </form>

            @endif

        @endif

    {{-- ========================= --}}
    {{-- SUMMER (3RD SEM) --}}
    {{-- ========================= --}}
    @elseif ($student->semester == 3)

        @if ($student->status == 'pending')

            <form action="{{ route('admin.promote.year', $student->id) }}" method="POST">
                @csrf
                <button class="btn btn-save">Promote Year</button>
            </form>

        @else

            <span>Completed</span>

        @endif

    @endif

</td>
                </tr>

            @endforeach

        </table>


    @endforeach

</div>

@endsection