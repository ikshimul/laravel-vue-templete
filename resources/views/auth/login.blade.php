<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | @yield('title', config('app.name'))</title>
    <!--favicon -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon" type="image/x-icon"/>
    <link href="{{ asset('assets/img/favicon.png') }}" rel="apple-touch-icon" type="image/x-icon"/>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    @yield('styles')
</head>
<body class="auth-body-bg">
    <div class="bg-overlay"></div>
    <div class="wrapper-page">
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body">

                    <div class="text-center mt-4">
                        <div class="mb-3">
                            <a href="/login" class="auth-logo">
                                <img src="{{url('/')}}/assets/images/logo-dark.png" height="30" class="logo-dark mx-auto" alt="">
                                <img src="{{url('/')}}/assets/images/logo-light.png" height="30" class="logo-light mx-auto" alt="">
                                <!-- <img src="{{url('/')}}/assets/images/banner.jpg" height="30" class="logo-dark mx-auto" alt=""> -->
                            </a>
                        </div>
                    </div>
                    <h4 class="text-muted text-center font-size-18"><b>Sign In</b></h4>
                    <div class="p-3" id="app">
                        <login></login>
                    </div>
                </div>
            </div>
        </div>
    </div>
@yield('scripts')
@vite('resources/js/login.js')
</body>
</html>