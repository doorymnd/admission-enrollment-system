<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <title>{{config('app.name', 'TMCC-Esystem')}}</title>
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminLayouts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminStudents.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminBadge.css') }}">
    <link rel="shortcut icon" style="opacity: 1" href="{{ asset('img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>

@php
$currentRoute = Route::currentRouteName();
@endphp
    
    <div class="sidebar">
    <a href="{{ url("/admin/dashboard") }}" class="logo-link">
        <img src="{{ asset('img/TMCC.png') }}" alt="Enrollment EsystemLogo" class="logo-img">
    </a>

    <ul>

        <li>
            <a href="{{ url("/admin/dashboard") }}">Dashboard</a>
        </li>

        <!-- STUDENT -->
        <li class="dropdown {{ str_contains($currentRoute, 'admin.students') ? 'active' : '' }}">
            <span class="dropdown-toggle">Student ▼</span>

            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.students.index') }}">Students</a></li>
                <li><a href="{{ route('admin.oldStudents') }}">Import Old Students</a></li>

                <li>
                    <a href="{{ route('admin.student.edit-request') }}">
                        Students Profile

                        @if(isset($profileRequestCount) && $profileRequestCount > 0)
                            <span class="badge-notif">{{ $profileRequestCount }}</span>
                        @endif
                    </a>
                </li>

                <li><a href="{{ route('admin.view.add.subject') }}">Add Subjects to Student</a></li>
            </ul>
        </li>

        <!-- ENROLLMENT -->
        <li class="dropdown">
            <span class="dropdown-toggle">
                Enrollment ▼
            </span>

            <ul class="dropdown-menu">

                <li>
                    <a href="{{ route('view.promote') }}">
                        Enrollments

                        {{-- Optional badge (example) --}}
                        @if($enrollmentPendingCount > 0)
                            <span class="badge-notif">{{ $enrollmentPendingCount }}</span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.irregular.pending') }}">
                        Irregular Enrollment

                        {{-- Optional badge-notif --}}
                        @if($irregularEnrollmentCount > 0)
                            <span class="badge-notif">{{ $irregularEnrollmentCount }}</span>
                        @endif
                    </a>
                </li>

            </ul>
        </li>

        <li>
            <a href="{{ route('admin.exam.upload') }}">Upload Exam Results</a>
        </li>

        <!-- FIXED WRONG UL STRUCTURE -->
        <li>
            <a href="{{ route('admin.admissions.index') }}">
                Manage Admission
                @if($admissionCount > 0)
                    <span class="badge-notif">{{ $admissionCount }}</span>
                @endif
            </a>
        </li>

        <!-- COURSE -->
        <li class="dropdown">
            <span class="dropdown-toggle">Course ▼</span>

            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.courses.coursesPanel') }}">Courses</a></li>
                <li><a href="{{ route('admin.curriculum.upload') }}">Upload Curriculum</a></li>
                <li><a href="{{ route('admin.courses.create') }}">Add Course</a></li>
                <li><a href="{{ route('admin.curriculum.viewsubject') }}">Add Subject</a></li>
                <li><a href="{{ route('admin.subjects.index') }}">View Subjects</a></li>
            </ul>
        </li>

        <li><a href="{{ route('admin.faculty') }}">Faculty/Staff</a></li>
        <li><a href="{{ route('admin.schedule.view') }}">View Schedule</a></li>

        <li><a href="{{ route('view.change.settings') }}">School Settings</a></li>
        <li><a href="{{ route('admin.grades.index') }}">Grades</a></li>
        <li><a href="{{ route('admin.accounts') }}">Accounts</a></li>
        <li><a href="{{ route('admin.logs') }}">Log</a></li>

        <li>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    Logout
                </button>
            </form>
        </li>

    </ul>
</div>

    @yield('content')


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    
    <script src="{{ asset('js/progressTracking.js') }}" ></script>
    <script src="{{ asset('js/filter.js') }}"></script>


    <script src="{{ asset('js/assignCourse.js') }}"></script> 
    <script src="{{ asset('js/drag&drop.js') }}"></script>  
    <script src="{{ asset('js/oldstudentprogress.js') }}"></script>
</body>
</html>