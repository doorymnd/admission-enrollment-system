<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name', 'TMCC-Esystem')}}</title>
    <link rel="shortcut icon" style="opacity: 1;" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>
    
    <header class="navbar">
        <div class="container">
            <a href="{{ url("/") }}" class="logo-link">
                <img src="{{ asset('img/TMCC.png') }}" alt="Enrollment EsystemLogo" class="logo-img">
            </a>
            
            <nav>
                <ul class="nav-links">
                    <li><a href="{{ url("/") }}">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contacts</a></li>
                    <li><a href="{{ route('student.login') }}">Login</a></li>
                    <li><a href="{{ route('admission.create') }}">Admission</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h2>Welcome to Our Enrollment System</h2>
            <p>Fast, simple, and secure student enrollment process.</p>
            <a href="/admission" class="btn-primary">Apply for Admission</a>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section id="about" class="section">
        <h2>About Us</h2>
        <p>
            Our school enrollment system provides an efficient way for students
            to apply, track admission status, and manage their academic journey.
        </p>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contact" class="section light-bg">
        <h2>Contact Us</h2>
        <p>Email: info@school.edu</p>
        <p>Phone: +63 900 000 0000</p>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <p>© {{ date('Y') }} Enrollment System. All rights reserved.</p>
    </footer>

</body>
</html>