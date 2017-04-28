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
        <div class="container2" >  
          <div class="div-logo">
            <a href="http://celebgramme.com"><div class="logo"></div></a>
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
						<p style="font-size:10px;">Dengan mengklik tombol sign-in berarti anda telah menyetujui<br>
						 <a href="">Terms & Conditions </a>pemakaian aplikasi celebgramme.com</p>
          {!! Form::close() !!}
          <a href="{{url('forgot-password')}}" class="forgot-link-landing"> Forgot password </a>
          <!--<a href="{{url('package')}}" class="register-link-landing"> Need an account ? </a>-->
					<div class="notif-user">
						@if (session('error') )
							<div class="alert alert-danger">
								<p align="center">{{session('error')}}</p>
							</div>
						@endif
						@if (session('success') )
							<div class="alert alert-success">
								<p align="center">{{session('success')}}</p>
							</div>
						@endif
					</div>
        </div>

				<!-- ADS 
				<div class="container3" style="position:fixed;bottom:0;width:100%;left:0;z-index:99;">  
					<div class="row">
						<div class="col-sm-12 col-md-12" style="position:relative;overflow:hidden;">
							<?php //echo $content; ?>
						</div>
					</div>
				</div>
				-->
      </div>
    </body>
</html>
