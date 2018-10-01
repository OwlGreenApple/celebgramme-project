  <!DOCTYPE html>
<html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Activfans</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/package.css') }}" rel="stylesheet">
      <script type="text/javascript" src="{{ asset('/js/jquery-1.11.3.js') }}"></script>
			<!-- Facebook Pixel Code -->
			<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window,document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			 fbq('init', '925095610957570'); 
			fbq('track', 'PageView');
			</script>
			<noscript>
			 <img height="1" width="1" 
			src="https://www.facebook.com/tr?id=925095610957570&ev=PageView
			&noscript=1"/>
			</noscript>
			<!-- End Facebook Pixel Code -->
			
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
            if ($("#password").val().length>25) {
              message += "password max 25 char";
              flag= true;
            }

            if (flag){
              e.preventDefault();
              alert(message);
            } else {
							fbq('track', 'CompleteRegistration');
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
            <a href="http://activfans.com"><div class="logo"></div></a>
          </div>
          <h1 class="h1-package">Register</h1>
        </div>
      </div>

      <div class="container">
        <div class="row content-all">
          <div class="col-md-2">
          </div>
          <div class="content-package container col-xs-12 col-sm-12 col-md-8">  

            <h3 class="price-list"> Registrasi User</h3>
            {!! Form::open(array('url'=>URL::ROUTE('auth.register'),'method'=>'post','class'=>"form-signin",)) !!}
              {!! csrf_field() !!}
  <!--          <form action="{{url('process-package')}}" method="POST" class="form-signin"> -->
              <div class="div-opsi-pembayaran">
                <div class="col-md-1">
                </div>
                <div class="col-xs-12 col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10">
                  <label class="col-xs-12 col-sm-12 col-md-6 control-label" for="formGroupInputSmall">Email Address</label>
                  <div class="col-xs-12 col-sm-12 col-md-6">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="">
                  </div>
                </div>  
                <div class="col-md-1">
                </div>
              </div>  

              <div class="div-opsi-pembayaran">
                <div class="col-md-1">
                </div>
                <div class="col-xs-12 col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10">
                  <label class="col-xs-12 col-sm-12 col-md-6 control-label" for="formGroupInputSmall">Password</label>
                  <div class="col-xs-12 col-sm-12 col-md-6">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password. min 6 char" value="">
                  </div>
                </div>  
                <div class="col-md-1">
                </div>
              </div>  

              <div class="div-opsi-pembayaran">
                <div class="col-md-1">
                </div>
                <div class="col-xs-12 col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10">
                  <label class="col-xs-12 col-sm-12 col-md-6 control-label" for="formGroupInputSmall">Password Confirmation</label>
                  <div class="col-xs-12 col-sm-12 col-md-6">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter confirmation password" value="">
                  </div>
                </div>  
                <div class="col-md-1">
                </div>
              </div>  

              <div class="div-opsi-pembayaran">
                <div class="col-md-1">
                </div>
                <div class="col-xs-12 col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10">
                  <label class="col-xs-12 col-sm-12 col-md-6 control-label" for="formGroupInputSmall">Nama Lengkap</label>
                  <div class="col-xs-12 col-sm-12 col-md-6">
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your fullname" value="">
                  </div>
                </div>  
                <div class="col-md-1">
                </div>
              </div>  


              <div class="div-opsi-pembayaran">
                <div class="col-md-1">
                </div>
                <div class="col-xs-12 col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10">
                    <p class="col-sm-12 col-md-10" style="text-align:center; font-size:11px;" > *Dengan mengklik "Register", anda menyetujui <a href="http://activfans.com/terms-conditions">Kebijakan Privasi dan Persyaratan Layanan </a></p>
                </div>  
                <div class="col-md-1">
                </div>
              </div>  


              <div class="row checkout-process">
                <button class="btn btn-lg btn-package btn-block" type="submit">Register</button>
              </div>
            </form>
            <p class="keterangan">
              <!--Jika anda mempunyai pertanyaan seputar activfans, silahkan baca FAQ ( Frequently Asked Question ) <a href="http://activfans.com/faq">disini</a> atau silahkan hubungi kami <a href="http://activfans.com/support">disini</a>
            --></p>
          </div>
          <div class="col-sm-2 col-md-2">
          </div>
        </div>
      </div>
    
      <div class="footer-package row container">
        <div class="footer-center container row">
          <div class="copyright col-md-6 col-sm-6 col-xs-12">
            Activfans.com © 2018
          </div>
					<div class="col-md-2 col-sm-2">
          </div>
          <div class="col-md-4 col-sm-4 col-xs-12 footer-helper ">
            <a href="http://activfans.com/blog">Blog </a>| 
            <a href="http://activfans.com/support">Support  </a>|  
            <a href="http://activfans.com/faq">FAQ  </a>| 
            <a href="http://activfans.com/prices">Prices </a>| 
            <a href="{{url('login')}}">Log in</a>
          </div>
          <div class="fn">
          </div>
        </div>
      </div>
    </body>
</html>
