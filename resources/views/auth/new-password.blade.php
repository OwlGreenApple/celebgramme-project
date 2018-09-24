<!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <?php if(env("APP_PROJECT") == 'Celebgramme') { ?>
        <link href="{{ asset('/css/sign-in.css') }}" rel="stylesheet">
      <?php } else { ?>
        <link href="{{ asset('/css/amelia/sign-in.css') }}" rel="stylesheet">
      <?php } ?>
      <script type="text/javascript" src="{{ asset('/js/jquery-1.11.3.js') }}"></script>
      <script>
        $(document).ready(function(){
          $('form').submit(function(e) {
            flag= false;
            message = "";
            if ($("#password").val()!=$("#password_confirmation").val()) {
              message += "password anda tidak sama dengan confirmation password";
              flag= true;
            } 
            if ($("#password").val().length<6) {
              message += "password min 6 char";
              flag= true;
            }

            if (flag){
              e.preventDefault();
              alert(message);
            } else {
              $(this).find("button[type='submit']").prop('disabled',true);
            }
          });      
        });       
      </script>
    </head>
    <body>
      <div class="div-black">
      </div>
      <div class="container">  
        <div class="container2">  
          <div class="div-logo">
            <a href="http://celebgramme.com"><div class="logo"></div></a>
          </div>
          {!! Form::open(array('url'=>URL::ROUTE('change.password'),'method'=>'post','class'=>"form-signin",)) !!}
            {!! csrf_field() !!}
              <label>Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="{{Input::old('password')}}">
              <label>Confirmation Password </label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter confirmation password" value="{{Input::old('password')}}">
            
            <button class="btn btn-lg btn-block" type="submit">Submit</button>
          {!! Form::close() !!}
        </div>

      </div>
    </body>
</html>
