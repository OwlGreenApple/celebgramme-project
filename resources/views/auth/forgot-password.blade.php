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
            <a href="http://celebgramme.com"><div class="logo"></div></a>
          </div>
					<div class="notif-user">
						@if (session('success') )
							<div class="alert alert-success">
								Kami telah mengirim link reset password ke email anda.
							</div>
						@endif
						@if (session('error') )
							<div class="alert alert-danger">
								Email belum terdaftar.
							</div>
						@endif
					</div>
          {!! Form::open(array('url'=>URL::ROUTE('auth.forgot'),'method'=>'post','class'=>"form-signin",)) !!}
            {!! csrf_field() !!}
					<h3>Password RESET</h3>
					<p style="font-family:'Open Sans'; font-size:12px;">Silahkan isi Email anda untuk RESET PASSWORD</p>
              <input type="email" class="form-control" id="username" name="username" placeholder="Enter email" value="{{Input::old('username')}}">
            
            <button class="btn btn-lg btn-block" type="submit">Submit</button>
          {!! Form::close() !!}
					<a href="{{url('login')}}" class="login-link-forgot"> Back to login </a>
        </div>

      </div>
    </body>
</html>
