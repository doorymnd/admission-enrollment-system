<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Exam Schedule</title>
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
            <h2>Entrance Examination Schedule</h2>
        </div>

        <div class="content">
            <p>Good day {{ $student->first_name }},</p>

            <p>You are scheduled to take your entrance exam:</p>

            <p><strong>Course:</strong> {{ $course }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($examDate)->format('F d, Y') }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $examTime)->format('h:i A') }}</p>

            <p>Please arrive at least <strong>30 minutes early</strong>.</p>

            <br>

            <p>Thank you,<br>{{ config('app.name') }}</p>

        <div class="footer">
            <p>If you did not expect this email, please contact the school administration immediately.</p>
        </div>
    </div>
</body>
</html>
