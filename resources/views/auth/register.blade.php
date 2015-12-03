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
          {!! Form::open(array('url'=>URL::ROUTE('auth.register'),'method'=>'post','class'=>"form-signin",)) !!}
            {!! csrf_field() !!}
              <label>Email Address</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="">
              <label>Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="">
              <label>Password Confirmation</label>
              <input type="password" class="form-control" id="password" name="password_confirmation" placeholder="Enter confirmation password" value="">
              <label>Nama Lengkap</label>
              <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your fullname" value="">
              <label>No. Handphone</label>
              <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter your phone number" value="">

              <p class="register-p" > Dengan mengklik "Register", anda menyetujui Kebijakan Privasi dan Persyaratan Layanan 
              </p>
            
            <button class="btn btn-lg btn-block" type="submit">Register</button>
          {!! Form::close() !!}
        </div>

      </div>

  
    </body>
</html>

