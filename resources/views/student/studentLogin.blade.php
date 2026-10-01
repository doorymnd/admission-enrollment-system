<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name', 'TMCC-Esystem')}}</title>
    <link rel="shortcut icon" href="{{ asset('img/RoundLogo.png') }}">
    <link rel="stylesheet" href="{{ asset("css/adminCSS/adminLogin.css") }}">
</head>
<body>
    
    <div class="login-container">

        <!-- LOGO -->
        <div class="login-header">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/TMCC.png') }}" alt="Logo">
            </a>
            <h2>Student Login</h2>
            <p>Sign in to access your student dashboard.</p>
        </div>

        <!-- LOGIN FORM -->
        <form method="POST" action="{{ route('student.login.submit') }}" autocomplete="off">
            @csrf
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" 
                value="{{ old('email') }}"
                autocomplete="new-email" required autofocus
                placeholder="Enter your Email">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" 
                autocomplete="new-password" required
                placeholder="Enter your Password">
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>
            {{-- <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div> --}}
        </form>

</body>
</html>