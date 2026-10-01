<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name', 'TMCC-Esystem')}}</title>
    <link rel="icon" href="{{ asset('img/RoundLogo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/admission/redirect.css') }}">
</head>
<body>

<div class="thankyou-card">
    <img src="https://cdn-icons-png.flaticon.com/512/148/148767.png" alt="Check Icon">
    <h3><strong>Thank You for Registering!</strong></h3>
    <p>
        <strong>Your registration has been received successfully.</strong>
        Please wait for the official announcement of the entrance examination schedule, 
        which will be posted on the <strong>TMCC Facebook page.</strong>
        Once the schedule is announced, you may proceed with your online entrance examination as instructed.
    </p>

    <a href="{{ url('/') }}" class="btn">Back to Home</a>
</div>
</body>
</html>