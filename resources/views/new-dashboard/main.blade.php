<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | New Activfans</title>
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
    <?php if(env("APP_PROJECT") == 'Celebgramme') { ?>
		  <link href="{{ asset('/new-dashboard/css/style.css') }}" rel="stylesheet">
    <?php } else { ?>
      <link href="{{ asset('/css/amelia/style.css') }}" rel="stylesheet">
    <?php } ?>
		<link href="{{ asset('/new-dashboard/css/jquery-ui.min.css') }}" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <?php if(env("APP_PROJECT") == 'Celebgramme') { ?>
		  <link href="{{ asset('/new-dashboard/css/themes/all-themes.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/main.css') }}" rel="stylesheet">
    <?php } else { ?>
      <link href="{{ asset('/css/amelia/themes/all-themes.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/amelia/main.css') }}" rel="stylesheet">
    <?php } ?>
		
		<!-- emoji -->
		<link href="{{ asset('/emoji/css/emojionearea.min.css') }}" rel="stylesheet">	


	
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
		<!-- emoji -->
		<script type="text/javascript">
				mainPathFolder = "<?php echo asset(''); ?>";
		</script>
		<script type="text/javascript" src="{{ asset('/emoji/js/prettify.js') }}"></script>
		<script type="text/javascript" src="{{ asset('/emoji/js/emojionearea.js') }}"></script>

		

		<script>
			$(document).ready(function(){
				$("#div-loading").hide();
			});
		</script>
		
		<?php if ( (Auth::user()->is_member_rico) || (Auth::user()->type=="admin") ) { ?>
			<script src="https://wchat.freshchat.com/js/widget.js"></script>
			<script>
				// Make sure fcWidget.init is included before setting these values
				// To set unique user id in your system when it is available
				window.fcWidget.setExternalId("<?php echo Auth::user()->id; ?>");
				// To set user name
				window.fcWidget.user.setFirstName("<?php echo Auth::user()->fullname; ?>");
				// To set user email
				window.fcWidget.user.setEmail("<?php echo Auth::user()->email; ?>");
			</script>
		<?php } ?>

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
                <a class="navbar-brand" href="index.html">
                  <?php if(env("APP_PROJECT") == 'Celebgramme') { ?>
                    <img id="mainImgLogo" class="img-responsive"src="{{asset('/new-dashboard/images/logo.png')}}">
                  <?php } else { ?>
                    <img id="mainImgLogo" class="img-responsive"src="{{asset('/new-dashboard/images/logo-amelia.png')}}">
                  <?php } ?>
                </a>
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
                            <i class="navigation-icon home-icon"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li id="dashboad" @if(Request::is('account')) class="active" @endif>
                        <a href="{{url('account')}}" data-identity="dashboard" class="waves-light">
                            <i class="navigation-icon dashboard-icon"></i>
                            <span>Account</span>
                        </a>
                    </li>
                    <li id="buyMore" @if(Request::is('buy-more')) class="active" @endif>
                        <a href="<?php if (Auth::user()->is_member_rico) { echo 'https://amelia.id/order.php'; } else { echo url('buy-more'); } ?>" data-identity="buyMore"  class="waves-light">
                            <i class="navigation-icon buy-icon"></i>
                            <span>Buy More</span>
                        </a>
                    </li>
					<li id="confirmPayment" @if(Request::is('confirm-payment')) class="active" @endif>
                        <a href="<?php if (Auth::user()->is_member_rico) { echo 'https://amelia.id/order.php'; } else { echo url('confirm-payment'); } ?>" data-identity="confirmPayment"  class="waves-light">
                            <i class="navigation-icon confirm-icon"></i>
                            <span>Confirm Payment</span>
                        </a>
                    </li>
					<li id="orderHistory" @if(Request::is('order')) class="active" @endif>
                        <a href="{{url('order')}}" data-identity="orderHistory"  class="waves-light">
                            <i class="navigation-icon history-icon"></i>
                            <span>Order History</span>
                        </a>
                    </li>
					<li id="changePassword" @if(Request::is('edit-profile')) class="active" @endif>
                        <a href="{{url('edit-profile')}}" data-identity="changePassword" class="waves-light">
                            <i class="navigation-icon settings-icon"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
										<!--
					<li id="faq">
                        <a href="http://celebgramme.freshdesk.com" target="_blank" data-identity="faq" class="waves-light">
                            <i class="navigation-icon FAQ-icon"></i>
                            <span>Faq & Support</span>
                        </a>
                    </li>
										-->
					<li id="faq">
                        <a href="<?php if (Auth::user()->is_member_rico) { echo 'http://bit.ly/faq-amelia-tool'; } else { echo 'https://activfans.com/faq'; } ?>" target="_blank" data-identity="faq" class="waves-light">
                            <i class="navigation-icon FAQ-icon"></i>
                            <span>FAQ</span>
                        </a>
                    </li>
					<?php if (!Auth::user()->is_member_rico) { ?>
					<li id="support">
												<a href="https://activfans.com/support-contact" data-identity="support" class="waves-light">
                            <i class="navigation-icon support-icon"></i>
                            <span>Support</span>
												</a>
                    </li>
					<?php } ?> 
					<li id="tutorial">
                        <a href="https://docs.google.com/document/u/1/d/1m9CuqNL-2-8g_g4UPHNtMXdH3lcwSjest-9z0xsUyNE/edit?usp=sharing" target="_blank" data-identity="tutorial" class="waves-light">
                            <i class="navigation-icon tutorial-icon"></i>
                            <span>Tutorial</span>
                        </a>
                    </li>
					<li id="logout">
                        <a href="{{url('logout')}}" data-identity="logout" class="waves-light">
                            <i class="navigation-icon logout-icon"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2018 <a href="{{url('')}}">Activfans</a>.
                    <b>Version: </b> 5.0.3
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
                            <i class="navigation-icon home-icon"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li @if(Request::is('account')) class="active" @endif id="dashboad">
                        <a href="{{url('account')}}" data-identity="dashboard" class="waves-light">
                            <i class="navigation-icon dashboard-icon"></i>
                            <span>Account</span>
                        </a>
                    </li>
                    <li id="buyMore" @if(Request::is('buy-more')) class="active" @endif>
                        <a href="<?php if (Auth::user()->is_member_rico) { echo 'https://amelia.id/order.php'; } else { echo url('buy-more'); } ?>" data-identity="buyMore"  class="waves-light">
                            <i class="navigation-icon buy-icon"></i>
                            <span>Buy More</span>
                        </a>
                    </li>
					<li id="confirmPayment" @if(Request::is('confirm-payment')) class="active" @endif>
                        <a href="<?php if (Auth::user()->is_member_rico) { echo 'https://amelia.id/order.php'; } else { echo url('confirm-payment'); } ?>" data-identity="confirmPayment"  class="waves-light">
                            <i class="navigation-icon confirm-icon"></i>
                            <span>Confirm Payment</span>
                        </a>
                    </li>
					<li id="orderHistory" @if(Request::is('order')) class="active" @endif>
                        <a href="{{url('order')}}" data-identity="orderHistory"  class="waves-light">
                            <i class="navigation-icon history-icon"></i>
                            <span>Order History</span>
                        </a>
                    </li>
					<li id="changePassword" @if(Request::is('edit-profile')) class="active" @endif>
                        <a href="{{url('edit-profile')}}" data-identity="changePassword" class="waves-light">
                            <i class="navigation-icon settings-icon"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
					<li id="faq">
                        <a href="<?php if (Auth::user()->is_member_rico) { echo 'http://bit.ly/faq-amelia-tool'; } else { echo 'https://activfans.com/faq'; } ?>" target="_blank" data-identity="faq" class="waves-light">
                            <i class="navigation-icon FAQ-icon"></i>
                            <span>FAQ</span>
                        </a>
                    </li>
					<?php if (!Auth::user()->is_member_rico) { ?>
					<li id="support">
												<a href="https://activfans.com/support-contact" data-identity="support" class="waves-light">
                            <i class="navigation-icon support-icon"></i>
                            <span>Support</span>
												</a>
                    </li>
					<?php } ?>
					<li id="tutorial">
                        <a href="https://docs.google.com/document/u/1/d/1m9CuqNL-2-8g_g4UPHNtMXdH3lcwSjest-9z0xsUyNE/edit?usp=sharing" target="_blank" data-identity="tutorial" class="waves-light">
                            <i class="navigation-icon tutorial-icon"></i>
                            <span>Tutorial</span>
                        </a>
                    </li>
					<li id="logout">
                        <a href="{{url('logout')}}" target="_blank" data-identity="logout" class="waves-light">
                            <i class="navigation-icon logout-icon"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2018 <a href="{{url('')}}">Activfans</a>.
                    <b>Version: </b> 5.1
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Right Sidebar -->
    </section>
		
		
    <section class="content">
			@yield('content')
		</section>
	
	
		<?php if ( (Auth::user()->is_member_rico) || (Auth::user()->type=="admin") ) { ?>
			<script>
				window.fcWidget.init({
					token: "660fa27c-cfa6-4fa3-b6c9-cd41aad6ab87",
					host: "https://wchat.freshchat.com"
				});
			</script>
		<?php } ?>
</body>

</html> 