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
        function commaSeparateNumber(val){
          while (/(\d+)(\d{3})/.test(val.toString())){
            val = val.toString().replace(/(\d+)(\d{3})/, '$1'+'.'+'$2');
          }
          return val;
        }
        function count_total() {
          total = price_daily_like + price_auto_manage - price_coupon;
          if (total<0) { total = 0;}
          $("#price-total").html(commaSeparateNumber(total));
        }
        $(document).ready(function() {
          $("#alert").hide();
          price_daily_like = 0; price_auto_manage = 0; price_coupon = 0;
          $( "#select-daily-like" ).change(function() {
            $("#price-daily-package").html($(this).find("option:selected").attr("data-price"));
            price_daily_like = parseInt($(this).find("option:selected").attr("data-real"));
            count_total();
          });          
          $( "#select-auto-manage" ).change(function() {
            $("#price-auto-manage").html($(this).find("option:selected").attr("data-price"));
            price_auto_manage = parseInt($(this).find("option:selected").attr("data-real"));
            count_total();
          });          

          $( "#text-coupon-code" ).keyup(function() {

            $.ajax({
                type: 'GET',
                url: "<?php echo url('calculate-coupon'); ?>",
                data: {
                  couponcode : $("#text-coupon-code").val()
                },
                dataType: 'text',
                beforeSend: function()
                {
                  $("#div-loading").show();
                },
                success: function(result) {
                  $("#div-loading").hide();
                  var data = jQuery.parseJSON(result);
                  $("#price-coupon").html(data.show);
                  price_coupon = parseInt(data.real);
                  count_total();
                }
            })


          });          

          $('#button-process').click(function(e){
            if ($("#select-auto-manage").find("option:selected").attr("data-real")=="0") 
            // if ( ($("#select-daily-like").find("option:selected").attr("data-real")=="0") && ($("#select-auto-manage").find("option:selected").attr("data-real")=="0") )
            {
              alert("Silahkan pilih paket yang akan anda pakai");
              // alert("Silahkan pilih salah satu paket. paket daily likes atau paket auto manage");
              e.preventDefault();
            }
          });

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

      <div class="row content-all">
        <div class="col-sm-2 col-md-2">
        </div>
        <div class="content-package container col-sm-8 col-md-8">  

          <h3 class="price-list"> Invoice Payment </h3>
          <form action="{{url('process-package')}}" method="POST" class="form-signin">
<!--
            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Paket Daily Likes</label>
                <div class="col-sm-4 col-md-4">
                  <select class="form-control" name="select-daily-like" id="select-daily-like">
                    <option data-real="0" data-price="" value="-">Silahkan pilih paket</option>
                    <option data-real="10000" data-price="10.000" value="1">1 Day - 200 likes</option>
                    <option data-real="15000" data-price="15.000" value="4">1 Day - 500 likes</option>
                    <option data-real="20000" data-price="20.000" value="7">1 Day - 1000 likes</option>
                    <option data-real="30000" data-price="30.000" value="10">1 Day - 2000 likes</option>
                    <option data-real="40000" data-price="40.000" value="13">1 Day - 3000 likes</option>
                    <option data-real="60000" data-price="60.000" value="2">7 Days - 200 likes</option>
                    <option data-real="90000" data-price="90.000" value="5">7 Days - 500 likes</option>
                    <option data-real="120000" data-price="120.000" value="8">7 Days - 1000 likes</option>
                    <option data-real="180000" data-price="180.000" value="11">7 Days - 2000 likes</option>
                    <option data-real="240000" data-price="240.000" value="14">7 Days - 3000 likes</option>
                    <option data-real="180000" data-price="180.000" value="3">28 Days - 200 likes</option>
                    <option data-real="270000" data-price="270.000" value="6">28 Days - 500 likes</option>
                    <option data-real="360000" data-price="360.000" value="9">28 Days - 1000 likes</option>
                    <option data-real="540000" data-price="540.000" value="12">28 Days - 2000 likes</option>
                    <option data-real="720000" data-price="720.000" value="15">28 Days - 3000 likes</option>
                  </select>
                </div>
                <div class="col-sm-1 col-md-1">
                  <p> Rp. </p>
                </div>
                <div class="col-sm-2 col-md-2">
                  <p class="price-checkout" id="price-daily-package"> 0</p>
                </div>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>
            </div>  
-->
            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <label class="col-xs-5 col-sm-5 control-label" for="formGroupInputSmall">Paket Auto Manage</label>
                <div class="col-sm-4 col-md-4">
                  <select class="form-control" name="select-auto-manage" id="select-auto-manage">
                    <option data-real="0" value="-">Silahkan pilih paket</option>
                    <option data-real="100000" data-price="100.000" value="16">Paket 7 Days</option>
                    <option data-real="175000" data-price="175.000" value="17">Paket 28 Days</option>
                    <option data-real="395000" data-price="395.000" value="18">Paket 88 Days</option>
                    <option data-real="695000" data-price="695.000" value="19">Paket 178 Days</option>
                    <option data-real="1285000" data-price="1.285.000" value="20">Paket 358 Days</option>
                  </select>
                </div>
                <div class="col-sm-1 col-md-1">
                  <p> Rp. </p>
                </div>
                <div class="col-sm-2 col-md-2">
                  <p class="price-checkout" id="price-auto-manage"> 0</p>
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
                  <input type="text" class="form-control" placeholder="Masukkan kode kupon anda" id="text-coupon-code" name="coupon-code">
                </div>
                <div class="col-sm-1 col-md-1">
                  <p> Rp. </p>
                </div>
                <div class="col-sm-2 col-md-2">
                  <p class="price-checkout" id="price-coupon">0</p>
                </div>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>             
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <hr>
              </div>  
              <div class="col-sm-2 col-md-2">
              </div>             
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-sm-2 col-md-2">
              </div>
              <div class="col-sm-8 col-md-8">
                <div class="col-xs-5 col-sm-5"></div>
                <div class="col-sm-4 col-md-4">
                  <label>Total</label>
                </div>
                <div class="col-sm-1 col-md-1">
                  <p> Rp. </p>
                </div>
                <div class="col-sm-2 col-md-2">
                  <p class="price-checkout" id="price-total">0</p>
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
            <div class="row checkout-process">
              <input class="btn-package" type="submit" value="Order" id="button-process">
            </div>
          </form>
          <p class="keterangan">Jika anda mempunyai pertanyaan seputar Celebgramme, silahkan baca FAQ ( Frequently Asked Question ) <a href="http://celebgramme.com/faq">disini</a> atau silahkan hubungi kami <a href="http://celebgramme.com/support">disini</a></p>
        </div>
        <div class="col-sm-2 col-md-2">
        </div>
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
