<!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/sign-in.css') }}" rel="stylesheet">
    </head>
    <body>
      <div class="div-black">
      </div>
      <div class="container">  
        <div class="container2">  
          <div class="div-logo">
            <div class="logo"></div>
          </div>
          {!! Form::open(array('url'=>URL::ROUTE('auth.login'),'method'=>'post','class'=>"form-signin",)) !!}
            {!! csrf_field() !!}
              <label>Email Address</label>
              <input type="email" class="form-control" id="username" name="username" placeholder="Enter email" value="{{Input::old('username')}}">
              <label>Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="{{Input::old('password')}}">
            
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="remember" id="remember"> <label for="remember">Remember me</label>
                </label>
              </div>      
            <button class="btn btn-lg btn-block" type="submit">Sign in</button>
          {!! Form::close() !!}
          <a href="{{url('register')}}" class="register-link-landing"> Need an account ? </a>
        </div>

      </div>
    </body>
</html>
