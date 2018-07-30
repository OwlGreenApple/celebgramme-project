  <!DOCTYPE html>
<html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Celebgramme</title>
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

			<script>
				fbq('track', 'AddPaymentInfo');
			</script>
			
      <script type="text/javascript">
        function commaSeparateNumber(val){
          while (/(\d+)(\d{3})/.test(val.toString())){
            val = val.toString().replace(/(\d+)(\d{3})/, '$1'+'.'+'$2');
          }
          return val;
        }
        function count_total() {
            $.ajax({
                type: 'GET',
                url: "<?php echo url('calculate-coupon'); ?>",
                data: {
                  couponcode : $("#text-coupon-code").val(),
									packageid : $("#select-auto-manage").val(),
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
									
									total = price_daily_like + price_auto_manage - price_coupon;
									if (total<0) { total = 0;}
									$("#price-total").html(commaSeparateNumber(total));
                }
            })
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
					$( "#select-auto-manage" ).change();

          $( "#text-coupon-code" ).keydown(function(e) {
						var key = e.which;
						if(key == 13)  // the enter key code
						{
							e.preventDefault();
							count_total();
							return false;  
						}
					});          
					
          $('#button-apply').click(function(e){
						count_total();
          });          
					
          $('#button-process').click(function(e){
            if ($("#select-auto-manage").find("option:selected").attr("data-real")=="0") 
            // if ( ($("#select-daily-like").find("option:selected").attr("data-real")=="0") && ($("#select-auto-manage").find("option:selected").attr("data-real")=="0") )
            {
              alert("Silahkan pilih paket yang akan anda pakai");
              // alert("Silahkan pilih salah satu paket. paket daily likes atau paket auto manage");
              e.preventDefault();
            }
						fbq('track', 'Purchase', {value: total, currency: 'IDR'});

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
          <h1 class="h1-package">Order Page</h1>
        </div>
      </div>

      <div class="container">
        <div class="row content-all">
        <div class="col-md-2">
        </div>
        <div class="content-package container col-xs-12 col-md-8">  

          <h3 class="price-list"> Order Package </h3>
          <form action="{{url('process-package')}}" method="POST" class="form-signin">
            <div class="div-opsi-pembayaran">
              <div class="col-md-1">
              </div>
              <div class="col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10 col-xs-12">
                <label class="col-xs-12 col-sm-12 col-md-4 control-label" for="formGroupInputSmall">Paket Auto Manage</label>
                <div class="col-xs-12 col-sm-9 col-md-5">
                  <select class="form-control" name="select-auto-manage" id="select-auto-manage">
                    <?php foreach($packages as $package) { ?>
                      <option data-real="{{$package->price}}" data-price="{{number_format($package->price,0,'','.')}}" value="{{$package->id}}" <?php if ($id==$package->id) echo "selected"; ?>>Paket {{$package->package_name}}</option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-xs-3 col-sm-1 col-md-1">
                  <p> Rp. </p>
                </div>
                <div class="col-xs-9 col-sm-2 col-md-2">
                  <p class="price-checkout" id="price-auto-manage"> 0</p>
                </div>
              </div>  
              <div class="col-md-1">
              </div>
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-md-1">
              </div>
              <div class="col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10 col-xs-12">
                <label class="col-xs-12 col-sm-12 col-md-4 control-label" for="formGroupInputSmall">Kode kupon (optional)</label>
                <div class="col-xs-12 col-sm-7 col-md-3">
                  <input type="text" class="form-control" placeholder="Kode kupon" id="text-coupon-code" name="coupon-code">
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2">
                  <input class="btn btn-default" type="button" value="Apply" id="button-apply">
                </div>
                <div class="col-xs-3 col-sm-1 col-md-1">
                  <p> Rp. </p>
                </div>
                <div class="col-xs-9 col-sm-2 col-md-2">
                  <p class="price-checkout" id="price-coupon">0</p>
                </div>
              </div>  
              <div class="col-md-1">
              </div>             
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-sm-1 col-md-1">
              </div>
              <div class="col-xs-12 col-sm-10 col-md-10">
                <hr>
              </div>  
              <div class="col-sm-1 col-md-1">
              </div>             
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-md-1">
              </div>
              <div class="col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10 col-xs-12">
                <div class="col-sm-5 col-md-5"></div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                  <label>Total</label>
                </div>
                <div class="col-xs-3 col-sm-1 col-md-1">
                  <p> Rp. </p>
                </div>
                <div class="col-xs-9 col-sm-2 col-md-2">
                  <p class="price-checkout" id="price-total">0</p>
                </div>
              </div>  
              <div class="col-md-1">
              </div>             
            </div>  

            <div class="div-opsi-pembayaran">
              <div class="col-md-1">
              </div>
              <div class="col-sm-offset-1 col-md-offset-0 col-sm-10 col-md-10 col-xs-12">
                <label class="col-xs-12 col-sm-12 col-md-4 control-label" for="formGroupInputSmall">Pilih Opsi Pembayaran Anda</label>
                <div class="col-xs-12 col-sm-9 col-md-5">
                  <select class="form-control" name="payment-method">
                    <option value="1">Bank transfer</option>
                    <!--<option value="2">Veritrans</option>-->
                  </select>
                </div>
              </div>  
              <div class="col-md-1">
              </div>             
            </div>  

            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="row checkout-process">
              <input class="btn-package" type="submit" value="Order" id="button-process">
            </div>
          </form>
          <p class="keterangan">
            <!--Jika anda mempunyai pertanyaan seputar Celebgramme, silahkan baca FAQ ( Frequently Asked Question ) <a href="http://celebgramme.com/faq">disini</a> atau silahkan hubungi kami <a href="http://celebgramme.com/support">disini</a>
          --></p>
        </div>
        <div class="col-sm-2 col-md-2">
        </div>
      </div>  
      </div>
      


      <div class="footer-package row container">
        <div class="footer-center container row">
          <div class="copyright col-xs-12 col-md-6 col-sm-6">
            Celebgramme.com © 2018
          </div>
					<div class="col-md-2 col-sm-2">
          </div>
          <div class="col-xs-12 col-md-4 col-sm-4 footer-helper ">
            <a href="http://celebgramme.com/blog">Blog </a>| 
            <a href="http://celebgramme.com/support">Support  </a>|  
            <a href="http://celebgramme.com/faq">FAQ  </a>| 
            <a href="http://celebgramme.com/prices">Prices </a>| 
            <a href="{{url('login')}}">Log in</a>
          </div>
          <div class="fn">
          </div>
        </div>
      </div>

    </body>
</html>
