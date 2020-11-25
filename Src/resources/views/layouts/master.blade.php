<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <base href="{{asset('')}}">

    <!-----------------START LINK CSS ------------------------------>
    <!-- fontawesome-->
    <link rel="stylesheet" href="css/lib/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="css/lib/ionicons.min.css"> --}}
    <!-- Select2 -->
    <link rel="stylesheet" href="css/lib/select2/css/select2.min.css">
    <link rel="stylesheet" href="css/lib/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!--  All Min Css -->
    <link rel="stylesheet" href="css/lib/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="css/lib/google_font_sans_pro.css">
    <!-- icheck-bootstrap -->
    {{-- <link rel="stylesheet" href="css/lib/icheck-bootstrap/icheck-bootstrap.min.css"> --}}
    <!-- DatetimePicker Bootstrap 4 -->
    <link rel="stylesheet" href="css/lib/bootstrap-datetimepicker.min.css">
    <!-- Custom master css -->
    <link rel="stylesheet" href="css/master.css">
    {{-- Custom CSS per page here --}}
    @yield('css')
    <!-----------------END LINK CSS ------------------------------->


    <!-----------------START LINK JAVASCRIPT ---------------------->
    <!-- jQuery -->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte.min.js"></script>
    <!-- Bootstrap Switch -->
    {{-- <script src="js/bootstrap-switch/js/bootstrap-switch.min.js"></script> --}}
    {{-- Custom JS per page here --}}
    @yield('js')
    <!-----------------END LINK JAVASCRIPT ------------------------>

</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav bg-custom">
                <li class="nav-item">
                    <a class="nav-link rg-menu" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars" style="color: darkgray;"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto info-user">
                {{-- show department name when user role --}}
                @if (Gate::allows('user-gate'))
                <li class="nav-item user-panel-custom">
                    <div class="user-deparment">{{ Auth::user()->department->name }}</div>
                </li>
                @endif
                {{-- Logged user name / Logout --}}
                @if (Auth::check())
                <li class="nav-item user-panel-custom">
                    <div class="info">
                        <a href="#" class="text-name">{{ Auth::user()->name }}</a>
                        /
                        <a href="{{ route('logout') }}" class="text-name" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                            {{ __('label.button.logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endif
                {{-- Locale selection --}}
                <x-locale />
            </ul>
        </nav>
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-primary elevation-4 sidebar-no-expand">
            <!-- Brand Logo -->
            <a href="/" class="brand-link">
                <img src="images/logo.png" alt="Logo" class="" style="max-width: 100%;">
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->

                {{-- Show menu by user's role --}}
                @if (Gate::allows('admin-gate'))
                @include('layouts.menu_admin')
                @else
                @include('layouts.menu_user')
                @endif
                <!-- /.sidebar-menu -->

            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        {{-- confirming popup --}}
        <x-popup-confirm />
    </div>
</body>

</html>
