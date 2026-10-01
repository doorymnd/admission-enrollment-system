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
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
</head>
<body>
    
    <body>
    <div class="sidebar">
        <a href="{{ url("/student/dashboard") }}" class="logo-link">
                <img src="{{ asset('img/TMCC.png') }}" alt="Enrollment EsystemLogo" class="logo-img">
        </a>
        <ul>
            <li><a href="{{ url('/student/dashboard') }}">Dashboard</a></li>

            <li><a href="{{ route('student.profile') }}">My Profile</a></li>

            <li><a href="{{ route('student.enrollments') }}">Enrollments</a></li>

            <li><a href="{{ route('student.grades') }}">Grades</a></li>

            <li><a href="{{ route('student.registration.list') }}">Registration Form</a></li>

            <li><a href="{{ route('student.change.password') }}">Change Password</a></li>
            
            <li>
                <form action="{{ route('student.logout') }}" method="POST" >
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    @yield('content')

    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
    <script src="{{ asset('js/progressTracking.js') }}" ></script>
        @yield('scripts') --}}

</body>
</html>