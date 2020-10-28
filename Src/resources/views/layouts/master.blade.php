<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>@yield('title')</title>

        <!-----------------START LINK CSS ------------------------------>
        <!-- Font Awesome -->        
        <link rel="stylesheet" href="public/css/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="public/css/ionicons.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="public/css/adminlte.min.css">
        <!-- jquery-ui css -->
        <link rel="stylesheet" href="public/css/jquery-ui.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="public/css/google_font_sans_pro.css" rel="stylesheet">
        <!-- custom css -->
        <link rel="stylesheet" href="public/css/master.css">
        <link rel="stylesheet" href="public/css/admin_102_shain_ichiran.css">
        <!-----------------END LINK CSS ------------------------------->


        <!-----------------START LINK JAVASCRIPT ---------------------->
        <!-- jQuery -->
        <script src="public/js/jquery/jquery.min.js"></script>
        <script src="public/js/jquery/jquery-ui.js"></script>
        <!-- Bootstrap 4 -->
        <script src="public/js/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="public/js/adminlte.min.js"></script>
        <!-- moment -->
        <script src="public/js/moment/moment.min.js"></script>
        <!-- date-range-picker -->
        <script src="public/js/daterangepicker/daterangepicker.js"></script>        
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="public/js/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Bootstrap Switch -->
        <script src="public/js/bootstrap-switch/js/bootstrap-switch.min.js"></script>
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
              <li class="nav-item user-panel-custom">
                <div class="user-deparment"></div>
              </li>
              <li class="nav-item user-panel-custom">
                <div class="info">
                    <a href="#" class="text-name">Admin</a>
                  </div>
              </li>
              <li class="nav-item user-panel-custom">
                <div class="tab">
                    <a class="tablink-VN" href="">VN</a>
                    <a class="tablink-EN" href="">EN</a>
                  </div>
              </li>
            </ul>
          </nav>
          <!-- Main Sidebar Container -->
          <aside class="main-sidebar sidebar-primary elevation-4 sidebar-no-expand">
            <!-- Brand Logo -->
            <a href="/" class="brand-link">
              <img src="public/images/logo.png" alt="Logo" class="" style="max-width: 100%;">
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
              <!-- Sidebar Menu -->

              <!-- SET QUYỀN USER TRUY CẬP ĐỂ HIỆN MENU ADMIN HOẶC USER -->
              <!-- @include('layouts.menu_user') -->              
                @include('layouts.menu_admin')        
              <!-- /.sidebar-menu -->

          </div>
            <!-- /.sidebar -->
          </aside>
          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
             @yield('content')
          </div>
        </div>
    </body>
</html>