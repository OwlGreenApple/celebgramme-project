  <!DOCTYPE html>
<html>
    <head>
      <title>Celebgramme</title>
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/images/celebgramme-favicon.png') }}">
      <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
      <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/bootstrap-theme.min.css') }}" rel="stylesheet">
      <link href="{{ asset('/css/package.css') }}" rel="stylesheet">
      <script type="text/javascript">
        $(document).ready(function() {
          $("#alert").hide();
        });
      </script>
    </head>
    <body>
      <div class="header-package row container">
        <div class="div-black">
          <div class="div-logo">
            <a href="http://celebgramme.com"><div class="logo"></div></a>
          </div>
          <h1 class="h1-package">Checkout</h1>
        </div>
      </div>

      <div class="content-package container row">  

        <h3 class="price-list"> Invoice Payment </h3>
        <form action="{{url('process-package')}}" method="POST" class="form-signin">

          <div class="div-opsi-pembayaran">
            <div class="col-sm-2 col-md-2">
            </div>
            <div class="col-sm-8 col-md-8">
              <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Paket Daily Likes</label>
              <div class="col-sm-4 col-md-4">
                <select class="form-control" name="payment-method">
                  <option value="-">Silahkan pilih paket</option>
                  <option value="1">1 Day - 200 likes</option>
                  <option value="4">1 Day - 500 likes</option>
                  <option value="7">1 Day - 1000 likes</option>
                  <option value="10">1 Day - 2000 likes</option>
                  <option value="13">1 Day - 3000 likes</option>
                  <option value="2">7 Days - 200 likes</option>
                  <option value="5">7 Days - 500 likes</option>
                  <option value="8">7 Days - 1000 likes</option>
                  <option value="11">7 Days - 2000 likes</option>
                  <option value="14">7 Days - 3000 likes</option>
                  <option value="3">28 Days - 200 likes</option>
                  <option value="6">28 Days - 500 likes</option>
                  <option value="9">28 Days - 1000 likes</option>
                  <option value="12">28 Days - 2000 likes</option>
                  <option value="15">28 Days - 3000 likes</option>
                </select>
              </div>
            </div>  
            <div class="col-sm-2 col-md-2">
            </div>
          </div>  

          <div class="div-opsi-pembayaran">
            <div class="col-sm-2 col-md-2">
            </div>
            <div class="col-sm-8 col-md-8">
              <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Paket Auto Manage</label>
              <div class="col-sm-4 col-md-4">
                <select class="form-control" name="payment-method">
                  <option value="-">Silahkan pilih paket</option>
                  <option value="16">Paket 7 Days</option>
                  <option value="17">Paket 28 Days</option>
                  <option value="18">Paket 88 Days</option>
                  <option value="19">Paket 178 Days</option>
                  <option value="20">Paket 358 Days</option>
                </select>
              </div>
            </div>  
            <div class="col-sm-2 col-md-2">
            </div>
          </div>  

          <div class="div-opsi-pembayaran">
            <div class="col-sm-2 col-md-2">
            </div>
            <div class="col-sm-8 col-md-8">
              <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Kode kupon (optional)</label>
              <div class="col-sm-4 col-md-4">
                <input type="text" class="form-control" placeholder="Masukkan kode kupon anda">
              </div>
            </div>  
            <div class="col-sm-2 col-md-2">
            </div>             
          </div>  

          <div class="div-opsi-pembayaran">
            <div class="col-sm-2 col-md-2">
            </div>
            <div class="col-sm-8 col-md-8">
              <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Pilih Opsi Pembayaran Anda</label>
              <div class="col-sm-4 col-md-4">
                <select class="form-control" name="payment-method">
                  <option value="1">Bank transfer</option>
          <!--        <option value="2">Veritrans</option>-->
                </select>
              </div>
            </div>  
            <div class="col-sm-2 col-md-2">
            </div>             
          </div>  

          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input class="btn-package" type="submit" value="Lanjutkan" id="button-process">
        </form>
        <p class="keterangan">Jika anda mempunyai pertanyaan seputar Celebgramme, silahkan baca FAQ ( Frequently Asked Question ) <a href="http://celebgramme.com/faq">disini</a> atau silahkan hubungi kami <a href="http://celebgramme.com/support">disini</a></p>
      </div>


      <div class="footer-package row container">
        <div class="footer-center container row">
          <div class="fl copyright col-md-7 col-sm-4">
            Celebgramme.com is NOT affiliated with Instagram.com & Facebook.com in anyway
          </div>
          <div class="col-md-5 col-sm-5 fl footer-helper ">
            <a href="http://celebgramme.com/about-us">About Us </a>| 
            <a href="http://celebgramme.com/about-us">Fitur </a>| 
            <a href="http://celebgramme.com/how-it-works">How It Works </a>  | 
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
