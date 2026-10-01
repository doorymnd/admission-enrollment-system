<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            border-radius: 6px 6px 0 0;
            text-align: center;
        }
        .content {
            padding: 15px;
        }
        .footer {
            font-size: 0.9em;
            color: #777;
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .password-box {
            background: #eee;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Your account has been created.</h2>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $faculty->first_name }} {{ $faculty->last_name }}</strong>,</p>

            <p>An account has been created for you in the TMCC System.</p>

            <p>
                Login URL: <a href="{{ url('/faculty/login') }}">{{ url('/faculty/login') }}</a>
                <br>
                <strong>Email: </strong><span class="password-box">{{ $faculty->email }}</span>
                 <br>
                <strong>Temporary Password: </strong><span class="password-box">{{ $temporaryPassword }}</span>
            </p>

            <p style="color: red">After logging in for the first time, you will be asked to create a new password.</p>

            <p>Thank you,<br>TMCC System Admin/p>
           
        </div>
<hr>
        <div class="footer">
            <p>If you did not expect this email, please contact the school administration immediately.</p>
        </div>
    </div>
</body>
</html>
