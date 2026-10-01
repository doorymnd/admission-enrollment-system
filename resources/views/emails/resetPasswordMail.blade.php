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
            <h2>Your password has been reset.</h2>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $admin->first_name }}</strong>,</p>

            <p>Your admin account password has been reset by a Super Admin.</p>

            <p><strong>New Password:</strong> <span class="password-box">{{ $newPassword }}</span></p>

            <p style="color: red">Please log in using this password and <strong>change it immediately for security reasons.</strong></p>

            <p>Thank you,<br>Admin Team</p>
           
        </div>
<hr>
        <div class="footer">
            <p>If you did not expect this email, please contact the school administration immediately.</p>
        </div>
    </div>
</body>
</html>
