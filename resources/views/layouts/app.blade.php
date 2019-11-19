<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @auth
    <meta http-equiv="refresh" content="300">
    @endauth
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/icon/favicon.ico') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/icon/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slicknav.min.css') }}">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="{{ asset('assets/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/component.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <!-- modernizr css -->
    <script src="{{ asset('assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
</head>

<body class="body-bg">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="horizontal-main-wrapper main-content">
        <!-- main header area start -->
        <div class="mainheader-area @guest border-bottom-0 @endguest">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="logo">
                            <a href="@guest{{ route('dashboard') }}@else{{ route('lapangan.index') }}@endguest"><img src="{{ asset('assets/images/icon/logo.png') }}" alt="logo"></a>
                        </div>
                    </div>
                    <!-- profile info & task notification -->
                    <div class="col-md-9 clearfix text-right">
                        @auth
                        <div class="d-md-inline-block d-block mr-md-4">
                            <ul class="notification-area">

                                {{-- <li id="full-view"><i class="ti-fullscreen"></i></li>
                                <li id="full-view-exit"><i class="ti-zoom-out"></i></li> --}}
                                {{-- Bell Notification --}}
                                <li class="dropdown">
                                    <i class="ti-bell dropdown-toggle" data-toggle="dropdown" id="notification">
                                        @if(Auth::user()->unreadNotifications()->count() > 0)
                                            <span id="notification-count">{{ Auth::user()->unreadNotifications()->count() }}</span>
                                        @endif
                                    </i>

                                    <div class="dropdown-menu bell-notify-box notify-box">
                                        <span class="notify-title" id="notification-title">Kamu memiliki {{ Auth::user()->unreadNotifications()->count() }} notifikasi baru {{-- <a href="#">view all</a> --}}</span>
                                        <div class="nofity-list">
                                            @forelse(Auth::user()->notifications as $notification)
                                                @if(class_basename($notification->type) == "NewOrder")
                                                    @include('user.notification.NewOrder', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderAccepted")
                                                    @include('user.notification.OrderAccepted', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderDenied")
                                                    @include('user.notification.OrderDenied', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderCanceled")
                                                    @include('user.notification.OrderCanceled', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderTLE")
                                                    @include('user.notification.OrderTLE', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "NewReview")
                                                    @include('user.notification.NewReview', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "ReviewReply")
                                                    @include('user.notification.ReviewReply', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderAlmostEndCS")
                                                    @include('user.notification.OrderAlmostEndCS', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderAlmostEndPO")
                                                    @include('user.notification.OrderAlmostEndPO', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderAlmostStartCS")
                                                    @include('user.notification.OrderAlmostStartCS', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderAlmostStartPO")
                                                    @include('user.notification.OrderAlmostStartPO', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderEndCS")
                                                    @include('user.notification.OrderEndCS', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderEndPO")
                                                    @include('user.notification.OrderEndPO', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderStartCS")
                                                    @include('user.notification.OrderStartCS', ['notification' => $notification])
                                                @elseif(class_basename($notification->type) == "OrderStartPO")
                                                    @include('user.notification.OrderStartPO', ['notification' => $notification])    
                                                @endif
                                            @empty
                                                <a href="#" class="notify-item">
                                                    <div class="notify-text">
                                                        <p>Notifikasi kosong</p>
                                                    </div>
                                                </a>
                                            @endforelse
                                        </div>
                                    </div>
                                </li>
                                {{-- <notification :userId="{{ Auth::user()->id }}" :unreads="{{ Auth::user()->unreadNotifications }}"></notification> --}}
                                {{-- Settings Icon --}}
                                <li class="settings-btn">
                                    <i class="ti-filter"></i>
                                </li>
                            </ul>
                        </div>
                        @endauth
                        <div class="clearfix @auth d-md-inline-block @else d-md-inline-flex @endif d-block">
                            @guest
                                <div class="user-profile m-0">
                                    <h4 class="user-name"><a style="color:white" href="{{ route('register') }}">Daftar</a></h4>
                                </div>
                                <div class="user-profile m-0">
                                    <h4 class="user-name"><a style="color:white" href="{{ route('login') }}">Masuk</a></h4>
                                </div>
                            @else
                            <div class="user-profile m-0">
                                {{-- <img class="avatar user-thumb" src="{{ asset('assets/images/user/default.png') }}" alt="avatar"> --}}
                                <h4 class="user-name dropdown-toggle" data-toggle="dropdown">{{ mb_strimwidth(Auth::user()->name, 0, 20, "...") }} <i class="fa fa-angle-down"></i></h4>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">Profile Saya</a>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main header area end -->

        <!-- header area start -->
        @auth
        <div class="header-area header-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-9 d-none d-lg-block">
                        @include('components.menu')
                        <!-- nav and search button -->
                        <div class="col-lg-3 clearfix">
                            <div class="search-box">
                                <form action="{{ route('lapangan.index') }}" method="GET">
                                    <input type="text" name="search" placeholder="Cari Lapangan" required>
                                    <i class="ti-search"></i>
                                </form>
                            </div>
                        </div>
                        <!-- mobile_menu -->
                        <div class="col-12 d-block d-lg-none">
                            <div id="mobile_menu"></div>
                        </div>
                    </div>
                </div>
        </div>
        @endauth
        <!-- header area end -->
        <!-- main content area start -->
        <div class="main-content-inner @hasSection('full-content') pb-0 @endif">
            @yield('full-content')
            @hasSection('content')
            <div class="container">
                @yield('content')
            </div>
            @endif
        </div>
        <!-- main content area end -->
        <!-- modal list start -->
        <div class="modal-list">
            @yield('modal')
        </div>
        <!-- modal list end -->
        <!-- footer area start-->
        <!-- footer area end-->
    </div>
    <footer>
        <div class="footer-area">
            <p>Copyright © 2018 <a href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a>. All right reserved.</p>
        </div>
    </footer>
    <!-- page container area end -->
    <!-- offset area start -->
    {{-- Isi Settings --}}
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
        <ul class="nav offset-menu-tab">
            <li><a class="active" data-toggle="tab" href="#filter">Filter Jenis Olahraga</a></li>
            {{-- <li><a data-toggle="tab" href="#settings">Settings</a></li> --}}
        </ul>
        <div class="offset-content tab-content">
            <div id="filter" class="tab-pane fadein show active">
                <form action="{{ route('lapangan.index') }}" method="GET">
                    <div class="offset-settings">
                        <h4>Jenis Olahraga</h4>
                        <div class="settings-list">
                            @foreach(App\LapanganOlahraga::$jenisOlahraga as $jenisOlahraga)
                                <div class="s-settings">
                                    <div class="s-sw-title">
                                        <h5>{{ $jenisOlahraga }}</h5>
                                        <div class="s-swtich">
                                            <input type="checkbox" name="filter[]" id="{{ $jenisOlahraga }}" value="{{ $jenisOlahraga }}">
                                            <label for="{{ $jenisOlahraga }}">Toggle</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center">
                                <button class="btn btn-flat btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- offset area end -->
    <!-- jquery latest version -->
    <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <!-- bootstrap 4 js -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slicknav.min.js') }}"></script>

    <!-- start chart js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <!-- start highcharts js -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- start zingchart js -->
    {{-- <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script>
        zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
        ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
    </script> --}}
    <!-- all line chart activation -->
    <script src="{{ asset('assets/js/line-chart.js') }}"></script>
    <!-- all pie chart -->
    <script src="{{ asset('assets/js/pie-chart.js') }}"></script>
    <!-- others plugins -->
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    @yield('script')
    <script type="text/javascript">
        $('#notification').parent().on('hide.bs.dropdown', function(){
            $('#notification-count').length > 0 ? $.get('{{ route('notification.read') }}') : '';
            $('#notification-count').length > 0 ? $('#notification-count').remove() : '';
            $('#notification-title').text('You have 0 new notifications');
        })
    </script>
</body>

</html>
