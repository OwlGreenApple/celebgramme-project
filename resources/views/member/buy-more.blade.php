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
    total = price_daily_like + price_auto_manage - price_coupon;
    if (total<0) { total = 0; }
    $("#price-total").html(commaSeparateNumber(total));
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
      });

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

  <!--
  <table class="table table-striped">
    <tr>
      <td>
        Daily Likes
      </td>
      <td>
        Price(1 day)
      </td>
      <td>
        Price(7 day)
      </td>
      <td>
        Price(28 day)
      </td>
    </tr>
    <tr>
      <td>
        200
      </td>
      <td>
        <input type="radio" name="package" value="1" id="a1" checked>
        <label for="a1">10.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="2" id="a2">
        <label for="a2">60.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="3" id="a3">
        <label for="a3">180.000</label>
      </td>
    </tr>
    <tr>
      <td>
        500
      </td>
      <td>
        <input type="radio" name="package" value="4" id="b1">
        <label for="b1">15.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="5" id="b2">
        <label for="b2">90.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="6" id="b3">
        <label for="b3">270.000</label>
      </td>
    </tr>
    <tr>
      <td>
        1000
      </td>
      <td>
        <input type="radio" name="package" value="7" id="c1">
        <label for="c1">20.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="8" id="c2">
        <label for="c2">120.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="9" id="c3">
        <label for="c3">360.000</label>
      </td>
    </tr>
    <tr>
      <td>
        2000
      </td>
      <td>
        <input type="radio" name="package" value="10" id="d1">
        <label for="d1">30.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="11" id="d2">
        <label for="d2">180.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="12" id="d3">
        <label for="d3">540.000</label>
      </td>
    </tr>
    <tr>
      <td>
        3000
      </td>
      <td>
        <input type="radio" name="package" value="13" id="e1">
        <label for="e1">40.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="14" id="e2">
        <label for="e2">240.000</label>
      </td>
      <td>
        <input type="radio" name="package" value="15" id="e3">
        <label for="e3">720.000</label>
      </td>
    </tr>
  </table>
-->

<!--
  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Paket Daily Likes</label>
    <div class="col-sm-8 col-md-6">
      <select class="form-control" name="package-daily-likes" id="select-daily-like">
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
  </div>  
-->
  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Paket Auto Manage</label>
    <div class="col-sm-8 col-md-6">
      <select class="form-control" name="package-auto-manage" id="select-auto-manage">
        <option data-real="0" value="-">Silahkan pilih paket</option>
				<!--
        <option data-real="100000" data-price="100.000" value="16" <?php if ($id==1) echo "selected"; ?>>Paket 7 Days</option>
        <option data-real="175000" data-price="175.000" value="17" <?php if ($id==2) echo "selected"; ?>>Paket 28 Days</option>
        <option data-real="395000" data-price="395.000" value="18" <?php if ($id==3) echo "selected"; ?>>Paket 88 Days</option>
        <option data-real="695000" data-price="695.000" value="19">Paket 178 Days</option>
        <option data-real="1285000" data-price="1.285.000" value="20">Paket 358 Days</option>
				-->
				<?php foreach($packages as $package) { ?>
					<option data-real="{{$package->price}}" data-price="{{number_format($package->price,0,'','.')}}" value="{{$package->id}}" <?php if ($id==$package->id) echo "selected"; ?>>
					Paket {{$package->package_name}}</option>
				<?php } ?>
      </select>
    </div>
  </div>  

  <div class="form-group form-group-sm row">
    <label class="col-xs-8 col-sm-4 control-label" for="formGroupInputSmall">Code coupon</label>
    <div class="col-sm-8 col-md-6">
      <input type="text" class="form-control" name="coupon-code" placeholder="Masukkan code coupon anda" id="text-coupon-code">
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
