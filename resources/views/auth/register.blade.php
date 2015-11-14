<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
    </head>
    <body>
    
    
      <div class="container">  
        {!! Form::open(array('url'=>URL::ROUTE('auth.register'),'method'=>'post','class'=>"form-signin",)) !!}
          {!! csrf_field() !!}
          <h2>Register</h2>
            <input type="text" class="form-control" id="email" name="email" placeholder="email" value="">
            <input type="password" class="form-control" id="password" name="password" placeholder="password" value="">
            <input type="password" class="form-control" id="password" name="password_confirmation" placeholder="password" value="">
          
          <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
        {!! Form::close() !!}
      </div>

  
    </body>
</html>

