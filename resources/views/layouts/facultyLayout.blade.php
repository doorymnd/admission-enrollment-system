<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name', 'TMCC-Esystem')}}</title>
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminLayouts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminCSS/adminStudents.css') }}">
    <link rel="stylesheet" href="{{ asset('css/studentCSS/studentDash.css') }}">
    <link rel="shortcut icon" style="opacity: 1" href="{{ asset('img/logo.png') }}">
</head>
<body>
    
    <body>
    <div class="sidebar">
        <a href="{{ route('faculty.facultyDash') }}" class="logo-link">
                <img src="{{ asset('img/TMCC.png') }}" alt="Enrollment EsystemLogo" class="logo-img">
        </a>
        <ul>
            <li><a href="{{ route('faculty.facultyDash') }}">Dashboard</a></li>
            <li><a href="{{ route('faculty.myStudents') }}">My Students</a></li>
            <li><a href="{{ route('faculty.mySubjects') }}">My Subjects</a></li>
            <li class="dropdown">
                <span class="dropdown-toggle">Grades ▼</span>
                <ul class="dropdown-menu">
                    <li><a href="{{ route( 'showForm.grades.upload') }}">Upload Grades</a></li>
                    <li><a href="{{ route('view.uploaded.grades') }}">View Grades</a></li>
                </ul>
            </li>
            <li><a href="{{ route('faculty.change.pass') }}">Change Password</a></li>
            <li>
                <form action="{{ route('faculty.logout') }}" method="POST" >
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
    <script src="{{ asset('js/progressTracking.js') }}" ></script>
        @yield('scripts') --}}

</body>
</html>