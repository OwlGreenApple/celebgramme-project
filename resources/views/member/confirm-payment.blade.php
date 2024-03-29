@extends('new-dashboard.main')

@section('content')
<script type="text/javascript">
  templike=0;
  function isNumberKey(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
        
      templike=$("#send-like").val();
      return true;
  }
  function cek_form() {
    var flag=true;
    error_message="";
				if($('#no_order').val()=="") {
					error_message+="No Order tidak boleh kosong <br>";
					flag=false;
				}
				if($('#nama_bank').val()=="") {
					error_message+="Nama Bank tidak boleh kosong <br>";
					flag=false;
				}
				if($('#no_rekening').val()=="") {
					error_message+="No Rekening tidak boleh kosong <br>";
					flag=false;
				}
				if($('#nama').val()=="") {
					error_message+="Nama Rekening tidak boleh kosong <br>";
					flag=false;
				}
				if($('#photo').val()=="") {
					error_message+="File tidak boleh kosong <br>";
					flag=false;
				}else{
				}
				var ext = $('#photo').val().split('.').pop().toLowerCase();
				if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
					error_message+="Extension file hanya boleh gif, png, jpg, jpeg <br>";
					flag=false;
				}else{
				}				
        
    if (flag==false) {
      $("#alert").addClass('alert-danger');
      $("#alert").removeClass('alert-success');
      $("#alert").show();
      $("#alert").html(error_message);
    }
    return flag;
  }  
  $(document).ready(function() {
		
		$("body").on('change', '#photo',
			function(e) {
				var f=this.files[0];
				
				if ((f.size>2000000)||(f.fileSize>2000000)){
				// if ((f.size>300000)||(f.fileSize>300000)){
					$(this).val('');
					alert('Image tidak boleh lebih besar dari 2MB');
				}
		});
		
    $("input:text").focus(function() { $(this).select(); } );
    $("#send-like").focus(function() { $(this).select(); } );
    $("#alert").hide();
		// $("body").on('change', '#no_order',function(e) {
		$("#no_order").keyup(function() {
      $.ajax({
          type: 'get',
          url: "<?php echo url('get-payment-total'); ?>",
          data: {
						no_order : $("#no_order").val()
					},
          dataType: 'text',
          beforeSend: function()
          {
            $("#div-loading").show();
          },
          success: function(result) {
						// $('#result').html(data);
						$("#div-loading").hide();
						console.log(result);
						$("#total").html(result);
						$("#hidden-total").val(result);
          }
      });

		
		});
		$('#no_order').keyup();
    $('#button-process').click(function(e){
      var uf = $('#form-confirm');
      var fd = new FormData(uf[0]);
      if (cek_form()==true) {
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: 'POST',
          url: "<?php echo url('process-payment'); ?>",
          data: fd,
          processData:false,
          contentType: false,
          dataType: 'text',
          beforeSend: function()
          {
            $("#div-loading").show();
          },
          success: function(result) {
              // $('#result').html(data);
              $("#div-loading").hide();
              var data = jQuery.parseJSON(result);
              $("#alert").show();
              $("#alert").html(data.message);
              if(data.type=='success')
              {
                $("#alert").addClass('alert-success');
                $("#alert").removeClass('alert-danger');
                $("#balance").val(data.balance);
                $("#span-balance").html(data.balance);
								
								<!-- Facebook Pixel Code celebpost purchased thank you page-->
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
								fbq('track', 'Purchase');
								<!-- End Facebook Pixel Code -->
              }
              else if(data.type=='error')
              {
                $("#alert").addClass('alert-danger');
                $("#alert").removeClass('alert-success');
              }
          }
      });
      }
    });
  });
</script>
<!-- Facebook Pixel Code celebpost purchased thank you page-->
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=298054080641651&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<div class="container-fluid">
  <form enctype="multipart/form-data" id="form-confirm">
    <div class="form-group form-group-sm row">
			<div class="col-sm-12 col-md-12">            
				<div class="alert btn-success col-sm-12 col-md-12" id="alert">
				</div>  
			</div>          
		</div>          
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">No. Order</label>
      <div class="col-sm-8 col-md-5">
        <input type="text" class="form-control" placeholder="No. Order" name="no_order" id="no_order" value="{{$no_order}}">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Nama Bank</label>
      <div class="col-sm-8 col-md-5">
        <input type="text" class="form-control" placeholder="Nama Bank" name="nama_bank" id="nama_bank">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">No Rekening</label>
      <div class="col-sm-8 col-md-5">
        <input type="text" class="form-control" placeholder="No Rekening" name="no_rekening" id="no_rekening">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Nama Pemilik Rekening</label>
      <div class="col-sm-8 col-md-5">
        <input type="text" class="form-control" placeholder="Nama Pemilik Rekening" name="nama" id="nama">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Jumlah Transfer</label>
      <div class="col-sm-8 col-md-5">
			<!--
        <input type="number" class="form-control" placeholder="Jumlah Transfer" name="total" onkeypress="return isNumberKey(event)" id="total" value=0>
				-->
				<input type="hidden" name="total" id="hidden-total">
				<label class="control-label" for="formGroupInputSmall" id="total"></label>
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Upload Bukti Transfer</label>
      <div class="col-sm-8 col-md-5">
        <input type="file" class="form-control" placeholder="" id="photo" name="photo">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <label class="col-xs-8 col-sm-4 col-md-3 control-label" for="formGroupInputSmall">Keterangan</label>
      <div class="col-sm-8 col-md-5">
        <input type="text" class="form-control" placeholder="Keterangan (optional)" name="keterangan">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
      <div class="col-xs-8 col-sm-4 col-md-3 ">
      </div>
      <div class="col-sm-8 col-md-3">
        <input class="btn btn-default" type="button" value="Process" id="button-process">
      </div>
    </div>  
    <div class="form-group form-group-sm row">
			<div class="col-sm-5 col-md-5" style="border-style: solid;border-width: 1px;"> 
				Apabila terjadi masalah saat melakukan konfirmasi, <br>silahkan lakukan konfirmasi pembayaran manual<br>Kirim data2 konfirmasi pembayaran ke email <br><span style="color:#2db8dc;font-weight:bold;">
        <?php if(env('APP_PROJECT')=='Celebgramme') { echo 'celebgramme@gmail.com'; } else { echo 'support@amelia.id'; } ?> </span><br>( no order, nama bank, nama pemilik rekening,<br>no rekening, bukti transfer(photo) & keterangan<br>
			</div>          
    </div>  
  </form>
</div>  
@endsection
