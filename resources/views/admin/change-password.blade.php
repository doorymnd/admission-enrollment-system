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
            <h2>Make a new password</h2>
            <p style="color: #666666"><small>Change your password to access your account.</small></p>
        </div>


            <form method="POST" action="{{ route('admin.change-password.update') }}">
                @csrf

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn-login">
                    Update Password
                </button>
            </form>

</body>
</html>