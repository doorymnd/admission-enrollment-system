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
            <h2>Admin Login</h2>
            <p>Sign in to manage the enrollment system</p>
        </div>

        <!-- LOGIN FORM -->
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <div class="form-group">
                <label>Username or Email</label>
                <input type="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>
        </form>

</body>
</html>