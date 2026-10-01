@extends('layouts.adminLayout')

@section('content')
<div class="main-content">


    <h1>Subjects Panel</h1>
    <p>Click a course to see its year levels. Manage prerequisites</p>
    <hr>


    <ul class="course-tree">

        @foreach ($courses as $course)
            @php


                // Only get the years that have subjects for this course
                $years = $course->subjects
                    ->pluck('year_level')
                    ->unique()
                    ->sort()
                    ->values();

                $yearNames = [
                    1 => '1st Year',
                    2 => '2nd Year',
                    3 => '3rd Year',
                    4 => '4th Year',
                ];
            @endphp


            <li x-data="{ openCourse: false }" class="course-item">

                <!-- COURSE FOLDER -->
                <div @click="openCourse = !openCourse" class="folder">
                    <span x-text="openCourse ? '📂' : '📁'"></span>
                    <strong>{{ $course->code }} - {{ $course->name }}</strong>
                </div>

                    <ul x-show="openCourse" x-transition class="year-list">
                        @foreach ($years as $year)
                            <li>
                                <a href="{{ route('admin.subjects.byYear', ['course_id' => $course->id, 'year' => $year]) }}"
                                class="folder year-folder">
                                    📁 {{ $yearNames[$year] ?? 'Year '.$year }}
                                    
                                </a>
                            </li>
                        {{-- @empty
                            <li style="color:#999;">No subjects yet</li> --}}
                        @endforeach
                    </ul>

            </li>
        @endforeach

    </ul>
</div>
@endsection
