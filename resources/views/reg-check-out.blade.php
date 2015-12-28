  <!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/package.css') }}" rel="stylesheet">
      <script type="text/javascript" src="{{ asset('/js/jquery-1.11.3.js') }}"></script>
      <script type="text/javascript">
        $(document).ready(function() {
          $("#alert").hide();
          // alert("Silahkan register dulu");

          $('form').submit(function(e) {
            flag= false;
            message = "";
            if ($("#password").val()!=$("#password_confirmation").val()) {
              message += "password anda tidak sama dengan password confirmation";
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
          <?php if (session()->has("error")) { ?>
            alert('email sudah terdaftar');
          <?php } ?>
        });
      </script>
    </head>
    <body>
      <div class="header-package row container">
        <div class="div-black">
          <div class="div-logo">
            <a href="http://celebgramme.com"><div class="logo"></div></a>
          </div>
          <h1 class="h1-package">Register</h1>
        </div>
      </div>

      <div class="row content-all">
        <div class="col-sm-2 col-md-2">
        </div>
        <div class="content-package container col-sm-8 col-md-8">  

<!--          <h3 class="price-list"> Silahkan register dulu.</h3>-->
          {!! Form::open(array('url'=>URL::ROUTE('auth.register'),'method'=>'post','class'=>"form-signin",)) !!}
            {!! csrf_field() !!}
<!--          <form action="{{url('process-package')}}" method="POST" class="form-signin"> -->
            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Email Address</label>
                <div class="col-sm-5 col-md-5">
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="">
                </div>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Password</label>
                <div class="col-sm-5 col-md-5">
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter password. min 6 char" value="">
                </div>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Password Confirmation</label>
                <div class="col-sm-5 col-md-5">
                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter confirmation password" value="">
                </div>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Nama Lengkap</label>
                <div class="col-sm-5 col-md-5">
                  <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your fullname" value="">
                </div>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>
            </div>  


            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                  <p class="col-xs-10 col-sm-10" style="text-align:center; font-size:11px;" > *Dengan mengklik "Register", anda menyetujui <a href="http://celebgramme.com/terms-conditions">Kebijakan Privasi dan Persyaratan Layanan </a></p>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>
            </div>  


            <div class="row checkout-process">
              <button class="btn btn-lg btn-package btn-block" type="submit">Register</button>
            </div>
          </form>
          <p class="keterangan">
            <!--Jika anda mempunyai pertanyaan seputar Celebgramme, silahkan baca FAQ ( Frequently Asked Question ) <a href="http://celebgramme.com/faq">disini</a> atau silahkan hubungi kami <a href="http://celebgramme.com/support">disini</a>
          --></p>
        </div>
        <div class="col-sm-2 col-md-2">
        </div>
      </div>


      <div class="footer-package row container">
        <div class="footer-center container row">
          <div class="fl copyright col-md-6 col-sm-3">
            Celebgramme.com is NOT affiliated with Instagram.com in anyway
          </div>
          <div class="col-md-6 col-sm-5 fl footer-helper ">
            <a href="http://celebgramme.com/our-products/auto-manage">Our Products </a>| 
            <a href="http://celebgramme.com/auto-manage">How It Works </a>  | 
            <a href="http://celebgramme.com/prices">Prices </a>| 
            <a href="http://celebgramme.com/blog">Blog </a>| 
            <a href="http://celebgramme.com/faq">FAQ  </a>| 
            <a href="http://celebgramme.com/support">Support  </a>|  
            <a href="{{url('login')}}">Log in</a>
          </div>
          <div class="fn">
          </div>
        </div>

      </div>
    </body>
</html>
