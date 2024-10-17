<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>Page Expired</title>
    <style>
        body, html {
            height: 100%;
        }
        .text {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100 text-center">
        <div>
            <img src="{{ asset('assets/images/page-expired-4x.png') }}" alt="Page Expired" class="img-fluid mb-0 w-75">
            <h1>Page Expired</h1>
            <p class="text mt-4">Please <a class="text-warning" href="{{ url()->previous() }}">click here</a> to continue</p>
        </div>
    </div>
</body>
</html>
