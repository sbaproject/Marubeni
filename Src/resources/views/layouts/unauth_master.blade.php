<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-----------------START LINK CSS ------------------------------>
    <base href="{{asset('')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/lib/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/lib/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="css/lib/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="css/lib/adminlte.min.css">
    <!-- jquery-ui css -->
    <link rel="stylesheet" href="css/lib/jquery-ui.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="css/lib/google_font_sans_pro.css">
    <!-- icheck-bootstrap -->
    <link rel="stylesheet" href="css/lib/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Custom master css -->
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/login.css">
    <!-----------------END LINK CSS ------------------------------->


    <!-----------------START LINK JAVASCRIPT ---------------------->
    <!-- jQuery -->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.js"></script>
    <!-- Bootstrap 4 -->
    {{-- <script src="js/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
    <!-- AdminLTE App -->
    {{-- <script src="js/adminlte.min.js"></script> --}}
    <!-- moment -->
    {{-- <script src="js/moment/moment.min.js"></script> --}}
    <!-- date-range-picker -->
    {{-- <script src="js/daterangepicker/daterangepicker.js"></script> --}}
    <!-- Tempusdominus Bootstrap 4 -->
    {{-- <script src="js/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script> --}}
    <!-- Bootstrap Switch -->
    {{-- <script src="js/bootstrap-switch/js/bootstrap-switch.min.js"></script> --}}
    <!-----------------END LINK JAVASCRIPT ------------------------>
</head>

<body class="hold-transition" style="background-color: #F3F3F3;">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="margin-left: 0px;">
            <!-- Left navbar links -->
            <ul class="navbar-nav bg-custom">
                <li class="nav-item">
                    <img src="images/logo.png" alt="Logo" class="" style="max-width: 100%;">
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto info-user">
                <x-locale/>
            </ul>
        </nav>

        <div class="login-box">
            @yield('content')
        </div>
    </div>
</body>

</html>