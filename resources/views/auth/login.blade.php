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
      <div class="container">  
        {!! Form::open(array('url'=>URL::ROUTE('auth.login'),'method'=>'post','class'=>"form-signin",)) !!}
          {!! csrf_field() !!}
          <h2>Please sign in</h2>
            <input type="email" class="form-control" id="username" name="username" placeholder="username" value="{{Input::old('username')}}">
            <input type="password" class="form-control" id="password" name="password" placeholder="password" value="{{Input::old('password')}}">
          
            <div class="checkbox">
              <label>
                <input type="checkbox" name="remember" id="remember"> <label for="remember">Remember me</label>
              </label>
            </div>      
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        {!! Form::close() !!}
      </div>
    </body>
</html>
