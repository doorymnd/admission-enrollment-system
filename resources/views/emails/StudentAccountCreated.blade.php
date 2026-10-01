<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Student Account</title>
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
            <h2>Student Account Created</h2>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $first_name }} {{ $last_name }}</strong>,</p>

            @if($password !== 'Your account already exists. Use your existing password.')
                <p>Congratulations! You have <strong>PASSED</strong> the entrance exam and have been accepted
                 into the <strong>{{ $course->name }}</strong> program at Trece Martires City College.
                 Your student account has been created. 
                 You can now log in to the student portal using the following credentials:</p>

                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Password:</strong> <span class="password-box">{{ $password }}</span></p>
            @else
                <p>It looks like you already have a student account. Please use your existing password to log in to the student portal.</p>
            @endif

            <p>You can access the student portal here:</p>
            <a class="btn" href="{{ route('student.login') }}">Go to Portal</a>

            <p>Thank you,<br>
            <strong>Trece Martires City College</strong></p>
        </div>

        <div class="footer">
            <p>If you did not expect this email, please contact the school administration immediately.</p>
        </div>
    </div>
</body>
</html>
