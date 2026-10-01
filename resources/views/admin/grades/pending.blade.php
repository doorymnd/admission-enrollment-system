@extends('layouts.adminLayout')

@section('content')

<div class="main-content">

    <div class="header-inline">
        <h1>Grades Approval</h1>
        <p>You can view the uploaded grades.</p>
    </div>
    <hr><br>


    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif


    <div style="margin:15px 0;">
        <a href="{{ route('admin.grades.index') }}" class="btn-all">All</a>
        <a href="{{ route('admin.grades.index', ['status'=>'submitted']) }}" class="btn-pending">Pending</a>
        <a href="{{ route('admin.grades.index', ['status'=>'approved']) }}" class="btn-approved">Approved</a>
        <a href="{{ route('admin.grades.index', ['status'=>'released']) }}" class="btn-released">Released</a>
    </div>


    @foreach($grades as $sectionKey => $subjects)

        @php
            $firstGroup = collect($subjects)->first();
            $firstGrade = is_iterable($firstGroup) ? collect($firstGroup)->first() : null;
        @endphp

        @if(!$firstGrade)
            @continue
        @endif

        @php
            $section = $firstGrade->section;
            $semester = $firstGrade->semester;
        @endphp

        <div style="margin-bottom:30px; border:2px solid #ccc; border-radius:8px; padding:15px;">

            <h4 style="background:#25770a; padding:10px; color: white; text-align: center;">
                {{ $section }}
            </h4>     
            <hr>  

            @foreach ($subjects as $subjectId => $group)
                @if(!is_iterable($group))
                        @continue
                    @endif

                    @php
                        $group = collect($group);
                        $first = $group->first();
                    @endphp

                    @if(!$first)
                        @continue
                    @endif

                    @php
                        $subjectName = optional($first->subject)->name ?? 'No Subject';
                        $subjectCode = optional($first->subject)->code ?? 'No Code';
                        $status = $first->status;
                    @endphp

                    <h3 style="background:#bbefaa; padding:10px;">
                        {{ $subjectCode }} | {{ $subjectName }}
                    </h3>

                    <table border="1" width="100%" cellpadding="8">
                            <tr>
                                <th>Student No</th>
                                <th>Name</th>
                                <th>Raw Grade</th>
                                <th>Final Grade</th>
                                <th>Status</th>
                            </tr>

                            @foreach($group as $grade)
                            <tr>
                                <td>{{ optional($grade->student)->student_no ?? 'N/A' }}</td>

                                <td>
                                    {{ optional(optional($grade->student)->info)->first_name ?? '' }}
                                    {{ optional(optional($grade->student)->info)->last_name ?? '' }}
                                </td>

                                <td>{{ $grade->raw_grade }}</td>
                                <td>{{ $grade->final_grade }}</td>
                                    <td>
                                        @if($grade->status === 'submitted')
                                            <span style="color:orange;">Waiting for Approval</span>

                                        @elseif($grade->status === 'approved')
                                            <span style="color: green">Ready for release</span>

                                        @elseif($grade->status === 'released')
                                            <span style="color:rgb(0, 81, 255);">Released</span>

                                        @elseif($grade->status === 'edit_request')
                                            <span style="color: orange;">Waiting</span>

                                        @else
                                            <span style="color:red;">Locked</span>
                                        @endif
                                    </td>
                            </tr>
                        @endforeach

                    </table>
            @endforeach

        </div>


    @endforeach

    </div>
    <script src="{{ asset('js/adminGradesPopUp.js') }}"></script>

    @endsection