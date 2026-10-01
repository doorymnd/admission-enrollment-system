<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Account Created</title>
</head>
<body>
    <h2>Welcome to the Student Portal</h2>

    <p>Dear {{ $student->studentInfo->first_name ?? 'Student' }},</p>

    <p>Your account has been created successfully.</p>

    <p><strong>Login Details:</strong></p>
    <p>
        Student No: {{ $student->student_no }} <br>
        Email: {{ $student->email }} <br>
        Password: {{ $password }}
    </p>

    <p>Please login and change your password immediately.</p>

    <br>
    <p>Regards,<br>Your School Administration</p>
</body>
</html>