<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | New CELEBGRAMME</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset('/images/celebgramme-favicon.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
		<link href="{{ asset('/new-dashboard/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
	
		<link href="{{ asset('/new-dashboard/css/font-awesome.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
		<link href="{{ asset('/new-dashboard/plugins/node-waves/waves.css') }}" rel="stylesheet">

    <!-- Animation Css -->
		<link href="{{ asset('/new-dashboard/plugins/animate-css/animate.css') }}" rel="stylesheet">

    <!-- Morris Chart Css-->
		<link href="{{ asset('/new-dashboard/plugins/morrisjs/morris.css') }}" rel="stylesheet">
	
	<!-- Noui css-->
		<link href="{{ asset('/new-dashboard/plugins/nouislider/nouislider.min.css') }}" rel="stylesheet">
	
	<!-- Bootstrap Tagsinput Css -->
		<link href="{{ asset('/new-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

   

    <!-- Css for hint -->
		<link href="{{ asset('/css/tooltipster.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/tooltipster-noir.css') }}" rel="stylesheet">
		
		
		
    <!-- Custom Css -->
		<link href="{{ asset('/new-dashboard/css/style.css') }}" rel="stylesheet">
		<link href="{{ asset('/new-dashboard/css/jquery-ui.min.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
		<link href="{{ asset('/new-dashboard/css/themes/all-themes.css') }}" rel="stylesheet">

		<link href="{{ asset('/css/main.css') }}" rel="stylesheet">


	
    <!-- Jquery Core Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/jquery/jquery.min.js') }}"></script>
	
    <!-- Bootstrap Core Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/bootstrap/js/bootstrap.js') }}"></script>
	
	

    <!-- Slimscroll Plugin Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/plugins/node-waves/waves.js') }}"></script>


    <!-- Js for hint -->
		<script type="text/javascript" src="{{ asset('/js/jquery.tooltipster.min.js') }}"></script>		
		
    <!-- Custom Js -->
		<script type="text/javascript" src="{{ asset('/new-dashboard/js/admin.js') }}"></script>

		
      <script>
        $(document).ready(function(){
          $("#div-loading").hide();
        });
        
      </script>
</head>

<body class="theme-default">
    <div id="div-loading">
      <div class="loadmain"></div>
      <div class="background-load"></div>
    </div>


    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-light-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <!-- <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a> -->
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="index.html"><img id="mainImgLogo" class="img-responsive"src="{{asset('/new-dashboard/images/logo.png')}}"></a>
			</div>
            <!-- <div class="collapse navbar-collapse" id="navbar-collapse"> -->
                <!-- <ul  class="nav navbar-nav navbar-right" > -->
					<!-- <li> -->
						<!-- <a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a> -->
					<!-- </li> -->
                <!-- </ul> -->
            <!-- </div> -->
			<a href="javascript:void(0);" id="navPhone" class="js-right-sidebar pull-right" data-close="true"><i class="fa fa-bars fa-2x"></i></a>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside class="sidebar">
            <!-- User Info -->
            <div class="user-info text-center padding-0">
                <h3 class="text-white navTitle">Navigation</h3>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li id="home" @if(Request::is('home')) class="active" @endif>
                        <a href="{{url('home')}}" data-identity="home" class="waves-light">
                            <i class="material-icons text-white">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li id="dashboad" @if(Request::is('dashboard')) class="active" @endif>
                        <a href="{{url('dashboard')}}" data-identity="dashboard" class="waves-light">
                            <i class="material-icons text-white">input</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li id="buyMore" @if(Request::is('buy-more')) class="active" @endif>
                        <a href="{{url('buy-more')}}" data-identity="buyMore"  class="waves-light">
                            <i class="material-icons text-white">shopping_cart</i>
                            <span>Buy More</span>
                        </a>
                    </li>
					<li id="confirmPayment" @if(Request::is('confirm-payment')) class="active" @endif>
                        <a href="{{url('confirm-payment')}}" data-identity="confirmPayment"  class="waves-light">
                            <i class="material-icons text-white">event_available</i>
                            <span>Confirm Payment</span>
                        </a>
                    </li>
					<li id="orderHistory" @if(Request::is('order')) class="active" @endif>
                        <a href="{{url('order')}}" data-identity="orderHistory"  class="waves-light">
                            <img src="{{asset('/new-dashboard/images/orderHistory.png')}}" >
                            <span>Order History</span>
                        </a>
                    </li>
					<li id="changePassword" @if(Request::is('edit-profile')) class="active" @endif>
                        <a href="{{url('edit-profile')}}" data-identity="changePassword" class="waves-light">
                            <i class="material-icons text-white">lock</i>
                            <span>Change Password</span>
                        </a>
                    </li>
					<li id="faq">
                        <a href="http://celebgramme.freshdesk.com" target="_blank" data-identity="faq" class="waves-light">
                            <img src="{{asset('/new-dashboard/images/faq.png')}}" >
                            <span>Faq & Support</span>
                        </a>
                    </li>
					<li id="tutorial">
                        <a href="https://drive.google.com/open?id=1uXCb2zKJdtq51j_dGkCYZvXe0R9XmnK_WMDyzgm1_PE" target="_blank" data-identity="tutorial" class="waves-light">
                            <i class="material-icons text-white">class</i>
                            <span>Tutorial</span>
                        </a>
                    </li>
					<li id="logout">
                        <a href="{{url('logout')}}" data-identity="logout" class="waves-light">
                            <i class="material-icons text-white">power_settings_new</i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2017 <a href="{{url('')}}">Celebgramme</a>.
                    <b>Version: </b> 3.3.5
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        
		<!-- My Right Sidebar -->
		<div id="myRightBar">
		</div>
        <!-- End My Right Sidebar -->
		
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
			<div class="user-info text-center padding-0">
                <h3 class="text-white navTitle">Navigation</h3>
            </div>
            <div class="menu" id="menuRight">
                <ul class="list ">
                    <li @if(Request::is('home')) class="active" @endif id="home">
                        <a href="{{url('home')}}" data-identity="home" class="waves-light">
                            <i class="material-icons text-white">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li @if(Request::is('dashboard')) class="active" @endif id="dashboad">
                        <a href="{{url('dashboard')}}" data-identity="dashboard" class="waves-light">
                            <i class="material-icons text-white">input</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li id="buyMore" @if(Request::is('buy-more')) class="active" @endif>
                        <a href="{{url('buy-more')}}" data-identity="buyMore"  class="waves-light">
                            <i class="material-icons text-white">shopping_cart</i>
                            <span>Buy More</span>
                        </a>
                    </li>
					<li id="confirmPayment" @if(Request::is('confirm-payment')) class="active" @endif>
                        <a href="{{url('confirm-payment')}};" data-identity="confirmPayment"  class="waves-light">
                            <i class="material-icons text-white">event_available</i>
                            <span>Confirm Payment</span>
                        </a>
                    </li>
					<li id="orderHistory" @if(Request::is('order')) class="active" @endif>
                        <a href="{{url('order')}}" data-identity="orderHistory"  class="waves-light">
                            <img src="{{asset('/new-dashboard/images/orderHistory.png')}}" >
                            <span>Order History</span>
                        </a>
                    </li>
					<li id="changePassword" @if(Request::is('edit-profile')) class="active" @endif>
                        <a href="{{url('edit-profile')}}" data-identity="changePassword" class="waves-light">
                            <i class="material-icons text-white">lock</i>
                            <span>Change Password</span>
                        </a>
                    </li>
					<li id="faq">
                        <a href="http://celebgramme.freshdesk.com" target="_blank" data-identity="faq" class="waves-light">
                            <img src="{{asset('/new-dashboard/images/faq.png')}}" >
                            <span>Faq & Support</span>
                        </a>
                    </li>
					<li id="tutorial">
                        <a href="https://drive.google.com/open?id=1uXCb2zKJdtq51j_dGkCYZvXe0R9XmnK_WMDyzgm1_PE" target="_blank" data-identity="tutorial" class="waves-light">
                            <i class="material-icons text-white">class</i>
                            <span>Tutorial</span>
                        </a>
                    </li>
					<li id="logout">
                        <a href="{{url('logout')}}" target="_blank" data-identity="logout" class="waves-light">
                            <i class="material-icons text-white">power_settings_new</i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2017 <a href="{{url('')}}">Celebgramme</a>.
                    <b>Version: </b> 3.3.5
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Right Sidebar -->
    </section>
		
		
    <section class="content">
			@yield('content')
		</section>
	
	

	
</body>

</html>