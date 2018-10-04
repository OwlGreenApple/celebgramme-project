<!DOCTYPE html>
<html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <?php if(env("APP_PROJECT") == 'Celebgramme') { ?>
        <link href="{{ asset('/css/sign-in.css') }}" rel="stylesheet">
      <?php } else { ?>
        <link href="{{ asset('/css/amelia/choose-tools.css') }}" rel="stylesheet">
      <?php } ?>
    </head>
    <body>
      <!-- Top Bar -->
      <div class="container-fluid navbar">
        <a href="{{url('/')}}">
          <img id="mainImgLogo" src="{{asset('/new-dashboard/images/logo-amelia.png')}}">
        </a>
      </div>

      <main>
        <div class="container">  
          <div class="container2">  

            <div class="col-xs-12" align="center">
              <h3>Pilih tool yang akan anda gunakan</h3>
              <br>
            </div>

            <div class="col-xs-2"></div>

            <div class="col-md-4 col-xs-12 div-btn-tools" align="center">
              <a href="{{url('/login')}}">
                <img class="btn-tools" src="{{asset('images/login-celebgramme.png')}}">  
              </a>
            </div>

            <div class="col-md-4 col-xs-12 div-btn-tools" align="center">
              <?php 
                $url = '';
                if (App::environment('local')) {
                  $url = 'http://localhost/celebpost/login';
                } else {
                  $url = 'https://celebpost.in/dashboard/login';
                }
              ?>
              <a href="{{$url}}">
                <img class="btn-tools" src="{{asset('images/login-celebpost.png')}}">
              </a>
            </div>
          </div>
        </div>    
      </main>
    
      <footer class="footer">
        © 2018 Amelia powered by ACTIVFANS
      </footer>
    </body>
</html>
