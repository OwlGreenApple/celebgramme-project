<!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/main.css') }}" rel="stylesheet">
      <script type="text/javascript" src="{{ asset('/js/jquery-1.11.3.js') }}"></script>
    </head>
    <body>
    
      <nav class="navbar navbar-inverse ">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="{{asset('/images/logo-celebgramme.png')}}" style="width:100%;max-width:200px;height:30px;"></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li></li>
            </ul>
          </div>
        </div>
      </nav>    
    
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-3 col-md-2 fl">    
            <ul class="nav nav-sidebar">
              <li><a href="{{url('send-like')}}">Send Likes</a></li>
              <li><a href="{{url('order')}}">Order History</a></li>
              <li><a href="{{url('order')}}">Buy More</a></li>
              <li><a href="{{url('edit-profile')}}">Profile</a></li>
              <li><a href="{{url('logout')}}">Logout</a></li>
            </ul>
          
          </div>
          
          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-0 fl">
            <div class="col-sm-4">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Daily Balance</h3>
                </div>
                <div class="panel-body">
                  5000
                </div>
              </div>
            </div>          
            <div class="col-sm-4">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Valid until</h3>
                </div>
                <div class="panel-body">
                  10 December 2015
                </div>
              </div>
            </div>          
            <div class="col-sm-4">
            </div>          
            <div class="col-sm-12">            
              <div class="alert alert-danger col-xs-5" id="alert">
                <strong>Oh snap!</strong> Change a few things up and try submitting again.
              </div>  
            </div>          
            <div class="col-sm-12">            
              @yield('content')
            </div>          
            
          </div>
          
          <div class="fn">
          </div>
          
        </div>
      </div>
    
    </body>
</html>
