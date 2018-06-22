@extends('new-dashboard.main')

@section('content')
<!-- Facebook Pixel Code celebpost initiate checkout pilih paket-->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '298054080641651');
  fbq('track', 'PageView');
  fbq('track', 'InitiateCheckout');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=298054080641651&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

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
            packageid : $("#select-auto-manage").val()
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
						
						
						if($('#daily-activity').is(':checked')) { 
							total = price_daily_like + price_auto_manage - price_coupon;
						}
						else if($('#max-account').is(':checked')) { 
							total = price_daily_like + price_max_account - price_coupon;
						}
						
						if (total<0) { total = 0; }
						$("#price-total").html(commaSeparateNumber(total));
						
          }
      });
			
  }
  $(document).ready(function() {
    $("#alert").hide();


    price_daily_like = 0; price_auto_manage = 0; price_max_account = 100000; price_coupon = 0;
    $( "#select-daily-like" ).change(function() {
      //$("#price-daily-package").html($(this).find("option:selected").attr("data-price"));
      price_daily_like = parseInt($(this).find("option:selected").attr("data-real"));
      count_total();
    });          
    $( "#select-auto-manage" ).change(function() {
      //$("#price-auto-manage").html($(this).find("option:selected").attr("data-price"));
      price_auto_manage = parseInt($(this).find("option:selected").attr("data-real"));
      count_total();
    });          
    $( "#select-maximum-account" ).change(function() {
      //$("#price-auto-manage").html($(this).find("option:selected").attr("data-price"));
			price_max_account = parseInt($(this).find("option:selected").attr("data-real"));
      count_total();
    });          
		

    $( "#text-coupon-code" ).keydown(function(e) {
			var key = e.which;
			if(key == 13)  // the enter key code
			{
				e.preventDefault();
				count_total();
				return false;  
			}
    });          

		$('#button-apply,#daily-activity,#max-account').click(function(e){
			count_total();
		});          

    $('#button-process').click(function(e){
      if ($("#select-auto-manage").find("option:selected").attr("data-real")=="0") 
      {
        alert("Silahkan pilih paket yang anda gunakan");
        // alert("Silahkan pilih salah satu paket. paket daily likes atau paket auto manage");
        e.preventDefault();
      }
    });


    <?php if (session()->has('message')) { ?> 
      $("#alert").addClass('alert-success');
      $("#alert").removeClass('alert-danger');
      $("#alert").show();
      $("#alert").html("<?php echo session("message"); ?>");
    <?php } ?>

    $( "#select-auto-manage" ).change();

		$('#daily-activity').click(function(e){
			$("#div-auto-manage").fadeIn(500);
			$('#div-maximum-account').fadeOut(500);
    });          
		
		$('#max-account').click(function(e){
			$("#div-maximum-account").fadeIn(500);
			$('#div-auto-manage').fadeOut(500);
    });          
		
  });
</script>
 
<form action="{{url('payment/process')}}" method="POST">

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-12">            
			<div class="alert btn-success col-sm-18 col-md-18" id="alert">
			</div>  
		</div>          
	</div>  
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<p>
			<strong>TRANSFER Melalui : </strong>
			</p>
		</div>  
		<div class="col-sm-4 col-md-3">
			<strong>Bank BCA</strong><br>
			4800-227-122<br>
			Sugiarto Lasjim<br><br>
		</div>  

		<div class="col-sm-4 col-md-4">
			<!--<strong>Bank Mandiri</strong><br>
			121-00-3592712-2<br>
			Sugiarto Lasjim<br><br>-->
		</div>  
	</div>  

  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Order Tipe</label>
    <div class="col-sm-8 col-md-5">
		<input type="radio" value="daily-activity" name="type" id="daily-activity" checked> <label for="daily-activity">Aktifitas harian</label> 
		<input type="radio" value="max-account" name="type" id="max-account"> <label for="max-account">Maksimum Akun</label> 
    </div>
  </div>  

  <div class="form-group form-group-sm row" id="div-auto-manage">
    <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Paket Auto Manage</label>
    <div class="col-sm-8 col-md-5">
      <select class="form-control" name="package-auto-manage" id="select-auto-manage">
				<?php foreach($packages as $package) { ?>
					<option data-real="{{$package->price}}" data-price="{{number_format($package->price,0,'','.')}}" value="{{$package->id}}" <?php if ($id==$package->id) echo "selected"; ?>>
					Paket {{$package->package_name}}</option>
				<?php } ?>
      </select>
    </div>
  </div>  

  <div class="form-group form-group-sm row" style="display:none;" id="div-maximum-account">
    <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Tambah Max Akun</label>
    <div class="col-sm-8 col-md-5">
      <select class="form-control" name="maximum-account" id="select-maximum-account">
					<option value="3" data-real="100000">Tambah 3 Akun</option>
					<option value="6" data-real="200000">Tambah 6 Akun</option>
					<option value="9" data-real="300000">Tambah 9 Akun</option>
      </select>
    </div>
  </div>  

  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Code coupon</label>
    <div class="col-sm-6 col-md-4">
      <input type="text" class="form-control" name="coupon-code" placeholder="Kode kupon" id="text-coupon-code">
    </div>
    <div class="col-sm-2 col-md-2">
			<input class="btn btn-default" type="button" value="Apply" id="button-apply">
    </div>
  </div>  

  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Metode Pembayaran</label>
    <div class="col-sm-8 col-md-5">
      <select class="form-control" name="payment-method">
        <option value="1">Bank transfer</option>
        <!--<option value="2">Veritrans</option>-->
      </select>
    </div>
  </div>  
  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Total</label>
    <div class="col-sm-1 col-md-1">
      <p>Rp. </p>
    </div>
    <div class="col-sm-2 col-md-2">
      <p id="price-total">0</p>
    </div>
  </div>  

  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input class="btn btn-default" type="submit" value="Process" id="button-process">
</form>

</div>  

@endsection
