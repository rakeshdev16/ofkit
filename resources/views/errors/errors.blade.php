<!doctype html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>אופקית - טיפול ויעוץ</title>
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
            @yield('section')
        </div>
    </div>
</body>
</html>
