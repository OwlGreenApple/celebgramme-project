@extends('member.index')

@section('content')
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
						
						
						total = price_daily_like + price_auto_manage - price_coupon;
						if (total<0) { total = 0; }
						$("#price-total").html(commaSeparateNumber(total));
						
          }
      });
			
  }
  $(document).ready(function() {
    $("#alert").hide();


    price_daily_like = 0; price_auto_manage = 0; price_coupon = 0;
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
      // if ( ($("#select-daily-like").find("option:selected").attr("data-real")=="0") && ($("#select-auto-manage").find("option:selected").attr("data-real")=="0") )
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

  });
</script>
 
<form action="{{url('payment/process')}}" method="POST">


  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Paket Auto Manage</label>
    <div class="col-sm-8 col-md-6">
      <select class="form-control" name="package-auto-manage" id="select-auto-manage">
				<?php foreach($packages as $package) { ?>
					<option data-real="{{$package->price}}" data-price="{{number_format($package->price,0,'','.')}}" value="{{$package->id}}" <?php if ($id==$package->id) echo "selected"; ?>>
					Paket {{$package->package_name}}</option>
				<?php } ?>
      </select>
    </div>
  </div>  

  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Code coupon</label>
    <div class="col-sm-6 col-md-4">
      <input type="text" class="form-control" name="coupon-code" placeholder="Kode kupon" id="text-coupon-code">
    </div>
    <div class="col-sm-2 col-md-2">
			<input class="btn btn-default" type="button" value="Apply" id="button-apply">
    </div>
  </div>  

  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Metode Pembayaran</label>
    <div class="col-sm-8 col-md-6">
      <select class="form-control" name="payment-method">
        <option value="1">Bank transfer</option>
        <!--<option value="2">Veritrans</option>-->
      </select>
    </div>
  </div>  
  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Total</label>
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

@endsection
