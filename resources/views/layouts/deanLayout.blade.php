<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name', 'TMCC-Esystem')}}</title>
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminLayouts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminStudents.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminBadge.css') }}">
    <link rel="shortcut icon" style="opacity: 1" href="{{ asset('img/logo.png') }}">
    
</head>
<body>
    
    <div class="sidebar">
        <a href="{{ url("/dean/dashboard") }}" class="logo-link">
                <img src="{{ asset('img/TMCC.png') }}" alt="Enrollment EsystemLogo" class="logo-img">
        </a>

        <ul>
            <li><a href="{{ url("/dean/dashboard") }}">Dashboard</a></li>
            <li><a href="{{ route('dean.grades.approval') }}">Grades</a></li>
            <li><a href="{{ route('dean.faculty.create') }}">Add Instructor</a></li>
            <li><a href="{{ route('dean.faculty') }}">Faculty Staff</a></li>
            <li><a href="{{ route('dean.schedule.view') }}">Scheduling</a></li>
            <li><a href="{{ route('dean.view.sched') }}">View Schedule</a></li>

            <li>
                <form action="{{ route('admin.logout') }}" method="POST" >
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    @yield('content')


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>