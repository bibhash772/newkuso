<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CudosApi | Admin</title>
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('fonts/fontawesome/css/fontawesome-all.min.css')}}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{asset('plugins/animation/css/animate.min.css')}}">
    <!-- notification css -->
    <link rel="stylesheet" href="{{asset('plugins/notification/css/notification.min.css')}}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body class="">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
        @yield('content')
        @include('layouts.partials.footer-login')
        @yield('footer-script')
</body>
</html>