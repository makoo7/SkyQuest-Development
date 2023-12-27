<!DOCTYPE html>
<html lang="{!! str_replace('_', '-', app()->getLocale()) !!}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>{!! $title !!} | {!! config('app.name') !!}</title>
    <!-- <link rel="shortcut icon" href="{!! asset('favicon.ico') !!}?v=1.1"> -->
    <link rel="icon" href="{!! asset('assets/backend/images/favicon.ico') !!}">
    <meta name="description" content="Welcome to {!! config('app.name') !!}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <link rel="stylesheet" href="{!! asset('assets/backend/css/all.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/backend/css/style.css') !!}?v={!!time()!!}">
    @yield('css')

    <script src="{!! asset('assets/backend/js/tinymce/tinymce.min.js') !!}" referrerpolicy="origin"></script>
</head>
<body>
    <div class="loader" style="display:none"><span class="loader-image"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span></div>
    @if(auth('admin')->check())
    <div class="page">
        <!-- Main Navbar-->
        <header class="header">
            <nav class="navbar">
                <div class="container-fluid">
                    <div class="navbar-holder d-flex align-items-center justify-content-between">
                        <!-- Navbar Header-->
                        <div class="navbar-header">
                            <!-- Navbar Brand -->
                            <a href="{!! route('admin.dashboard') !!}" class="navbar-brand"><!-- d-none d-sm-inline-block -->
                                <div class="brand-text">
                                    <img src="{!! asset('assets/backend/images/logo.webp') !!}" class="logo-img d-none d-sm-inline-block">
                                    <img src="{!! asset('assets/backend/images/logo.webp') !!}" class="img-fluid d-sm-none">
                                </div>
                            </a>
                            <!-- Toggle Button-->
                            <a id="toggle-btn" href="javascript:void(0)" class="menu-btn active"><span></span><span></span><span></span></a>
                        </div>
                        <!-- Navbar Menu -->
                        <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                            <!-- Settings dropdown    -->
                            <li class="nav-item dropdown">
                                <a id="settings" rel="nofollow" data-target="#" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language">
                                    <i class="fa fa-cog"></i><span class="d-none d-sm-inline-block">Settings</span>
                                </a>
                                <ul aria-labelledby="settings" class="dropdown-menu">
                                    <li>
                                        <a rel="nofollow" href="{!! route('admin.profile') !!}" class="dropdown-item"> <i class="fa fa-user"></i> Manage Profile</a>
                                    </li>
                                    <li>
                                        <a rel="nofollow" href="{!! route('admin.changepassword') !!}" class="dropdown-item"> <i class="fa fa-key"></i> Change Password</a>
                                    </li>
                                    @if(auth('admin')->user()->can('system-settings'))
                                    <li>
                                        <a rel="nofollow" href="{!! route('admin.systemsettings') !!}" class="dropdown-item"> <i class="fa fa-cog"></i> System Settings</a>
                                    </li>
                                    @endif
                                    @if(auth('admin')->user()->can('report-pricing'))
                                    <li>
                                        <a rel="nofollow" href="{!! route('admin.reportpricing') !!}" class="dropdown-item"><i class="fa-solid fa-file-invoice-dollar"></i>Manage Report Pricing</a>
                                    </li>
                                    @endif
                                    @if(auth('admin')->user()->can('publish-date'))
                                    <li>
                                        <a rel="nofollow" href="{!! route('admin.publishdate') !!}" class="dropdown-item"><i class="fa-solid fa-file-invoice-dollar"></i>Publish Date Settings</a>
                                    </li>
                                    @endif
                                    @if(auth('admin')->user()->can('report-forecast-settings'))
                                    <li>
                                        <a rel="nofollow" href="{!! route('admin.reportforecastsettings') !!}" class="dropdown-item"><i class="fa fa-cog"></i>Report Forecast Settings</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            <!-- Logout    -->
                            <li class="nav-item"><a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="nav-link logout">
                                <span class="d-none d-sm-inline">Logout</span><i class="fa fa-sign-out"></i></a>
                            </li>
                            <form id="admin-logout-form" action="{!! route('admin.logout') !!}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="page-content d-flex align-items-stretch">
            @include("admin.layouts.navigation")
            <div class="content-inner">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">{!! $title !!}</h2>
                    </div>
                </header>
                @yield('content')
                <footer class="main-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>{!! config('app.name')!!} &copy; {!!date('Y')!!}</p>
                            </div>
                            <div class="col-sm-6 copyrights text-right">
                                <p>Powered by <a href="{!! config('constants.POWERED_BY_URL') !!}" target="_blank">{!! config('app.name')!!}</a></p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <!-- JavaScript files-->
    @else
    @yield('content')
    @endif

    @include("admin.layouts.js")
</body>
</html>